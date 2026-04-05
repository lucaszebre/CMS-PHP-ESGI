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

        if (!$this->authSession->canManagePages()) {
            $this->redirect('/');
        }
    }

    public function index(Request $request)
    {
        $this->render('admin/pages/index', [
            'pages' => $this->page->getAllPages(),
            'error' => $this->errorMessage(trim($request->query('error'))),
            'isAdmin' => $this->authSession->isAdmin(),
        ]);
    }

    public function showCreate(Request $request)
    {
        $this->render('admin/pages/create', [
            'error' => $this->errorMessage(trim($request->query('error'))),
            'statuses' => Page::STATUSES,
        ]);
    }

    public function create(Request $request)
    {
        $content = trim($request->input('content'));
        $slug = trim($request->input('slug'));
        $status = $request->input('status');

        if (!$this->isValidStatus($status)) {
            $this->redirect('/admin/pages/create?error=invalid-status');
        }

        if (!$this->isValidContentLength($content)) {
            $this->redirect('/admin/pages/create?error=content-too-long');
        }

        if ($this->page->slugExists($slug)) {
            $this->redirect('/admin/pages/create?error=slug-taken');
        }

        if (!$this->page->addPage(
            trim($request->input('title')),
            $content,
            $status,
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            $slug,
        )) {
            $this->redirect('/admin/pages/create?error=save-failed');
        }

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
            'error' => $this->errorMessage(trim($request->query('error'))),
            'statuses' => Page::STATUSES,
        ]);
    }

    public function update(Request $request)
    {
        $content = trim($request->input('content'));
        $id = (int) $request->param('id');
        $slug = trim($request->input('slug'));
        $status = $request->input('status');

        if (!$this->isValidStatus($status)) {
            $this->redirect('/admin/pages/edit/' . $id . '?error=invalid-status');
        }

        if (!$this->isValidContentLength($content)) {
            $this->redirect('/admin/pages/edit/' . $id . '?error=content-too-long');
        }

        if ($this->page->slugExists($slug, $id)) {
            $this->redirect('/admin/pages/edit/' . $id . '?error=slug-taken');
        }

        $updateResult = $this->page->updatePage(
            $id,
            trim($request->input('title')),
            $content,
            $status,
            $this->authSession->username() ?? '',
            date('Y-m-d H:i:s'),
            $slug,
        );

        if ($updateResult === Page::UPDATE_RESULT_NOT_FOUND) {
            $this->redirect('/admin/pages?error=page-not-found');
        }

        if ($updateResult === Page::UPDATE_RESULT_FAILED) {
            $this->redirect('/admin/pages/edit/' . $id . '?error=save-failed');
        }

        $this->redirect('/admin/pages');
    }

    public function delete(Request $request)
    {
        if (!$this->authSession->isAdmin()) {
            $this->redirect('/admin/pages?error=forbidden');
        }

        $page = $this->page->getPageById((int) $request->param('id'));

        if ($page) {
            $this->page->removePage($page['id']);
        }

        $this->redirect('/admin/pages');
    }

    private function isValidContentLength(string $content): bool
    {
        return preg_match('/^.{0,' . Page::MAX_CONTENT_LENGTH . '}$/us', $content) === 1;
    }

    private function isValidStatus(string $status): bool
    {
        return in_array($status, array_keys(Page::STATUSES), true);
    }

    private function errorMessage(string $errorCode): string
    {
        return match ($errorCode) {
            'content-too-long' => 'Content must be 16,000 characters or fewer.',
            'page-not-found' => 'The page no longer exists.',
            'save-failed' => 'The page could not be saved. Please try again.',
            'slug-taken' => 'This slug is already used by another page.',
            'invalid-status' => 'Invalid status selected.',
            'forbidden' => 'You do not have permission to perform this action.',
            default => '',
        };
    }
}
