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
        $this->page->addPage(
            trim($request->input('title')),
            trim($request->input('content')),
            $request->input('status'),
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            trim($request->input('slug')),
        );

        $this->redirect('/admin/pages');
    }

    public function showEdit(Request $request)
    {
        $page = $this->page->getPageById((int) $request->param('id'));

        if (!$page) {
            $this->redirect('/admin/pages');
        }

        $this->render('admin/pages/edit', ['page' => $page]);
    }

    public function update(Request $request)
    {
        $id = (int) $request->param('id');

        $this->page->updatePage(
            $id,
            trim($request->input('title')),
            trim($request->input('content')),
            $request->input('status'),
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            trim($request->input('slug')),
        );

        $this->redirect('/admin/pages');
    }

    public function delete(Request $request)
    {
        $page = $this->page->getPageById((int) $request->param('id'));

        if ($page) {
            $this->page->removePage($page['slug']);
        }

        $this->redirect('/admin/pages');
    }
}
