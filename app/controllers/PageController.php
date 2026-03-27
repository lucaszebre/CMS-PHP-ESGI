<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Page;

class PageController extends Controller
{
    private Page $page;

    public function __construct()
    {
        $this->page = new Page();
    }

    public function list(Request $request)
    {
        $pages = $this->page->getPublishedPages();

        $this->render('pages/list', ['pages' => $pages]);
    }

    public function show(Request $request)
    {
        $slug = $request->param('slug');
        $page = $this->page->getPage($slug);

        if (!$page || $page['status'] !== 'published') {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $this->render('pages/show', ['page' => $page]);
    }
}
