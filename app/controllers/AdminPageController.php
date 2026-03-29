<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Page;
use App\Services\AuthSession;

class AdminPageController extends Controller
{
    private Page $page;
    private AuthSession $authSession;

    public function __construct()
    {
        $this->page = new Page();
        $this->authSession = new AuthSession();

        if (!$this->authSession->isLoggedIn()) {
            $this->redirect('/login');
        }

        $role = $this->authSession->role();

        if ($role !== 'admin' && $role !== 'editor') {
            $this->redirect('/');
        }
    }

    public function index(Request $request)
    {
        $this->render('admin/pages/index', [
            'pages' => $this->page->getAllPages(),
        ]);
    }

    public function showCreate(Request $request)
    {
        $this->render('admin/pages/create', [
            'error' => trim($request->query('error')),
        ]);
    }

    public function create(Request $request)
    {
        $slug = trim($request->input('slug'));

        if ($this->page->slugExists($slug)) {
            $this->redirect('/admin/pages/create?error=slug-taken');
        }

        $this->page->addPage(
            trim($request->input('title')),
            trim($request->input('content')),
            $request->input('status'),
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            $slug,
        );

        $this->redirect('/admin/pages');
    }

    public function showEdit(Request $request)
    {
        $page = $this->page->getPageById((int) $request->param('id'));

        if (!$page) {
            $this->redirect('/admin/pages');
        }

        $this->render('admin/pages/edit', [
            'page' => $page,
            'error' => trim($request->query('error')),
        ]);
    }

    public function update(Request $request)
    {
        $id = (int) $request->param('id');
        $slug = trim($request->input('slug'));

        if ($this->page->slugExists($slug, $id)) {
            $this->redirect('/admin/pages/edit/' . $id . '?error=slug-taken');
        }

        $this->page->updatePage(
            $id,
            trim($request->input('title')),
            trim($request->input('content')),
            $request->input('status'),
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            $slug,
        );

        $this->redirect('/admin/pages');
    }

    public function delete(Request $request)
    {
        $page = $this->page->getPageById((int) $request->param('id'));

        if ($page) {
            $this->page->removePage($page['id']);
        }

        $this->redirect('/admin/pages');
    }
}
