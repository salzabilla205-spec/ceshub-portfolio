<?php
namespace App\Http\Controllers;
class InventoryTemplateController extends Controller
{
    public function __invoke()
    {
        return response()->download(resource_path('templates/template-barang-ces.xlsx'), 'Template Barang CES.xlsx');
    }
}
