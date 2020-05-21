<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter['pageSize'] = intval($request->input('pageSize', 20));
        $filter['pageNum'] = intval($request->input('pageNum', 1));
        $filter['keyword'] = $request->input('keyword', '');
        $filter['cat_id'] = $request->input('cat_id', 0);
        $filter['order_by'] = $request->input('order_by', 'default');

        $where = [];
        if (!empty($filter['keyword']))
        {
            $where[] = ['title', 'like', "%{$filter['keyword']}%"];
        }
        if (!empty($filter['cat_id']))
        {
            $where[] = ['cat_id', '=', $filter['cat_id']];
        }
        $order_by = (new Product)->getOrderBy($filter['order_by']);
        $offset = ($filter['pageNum'] - 1) * $filter['pageSize'];

        $product = Product::withoutGlobalScope('avaiable')->where($where);
        $total = $product->count();
        $list = $product->orderBy($order_by[0], $order_by[1])
            ->offset($offset)->limit($filter['pageSize'])->get();
        foreach ($list as &$item)
        {
            $item['image_host'] = 'http://127.0.0.1:8000';
        }
        unset($item);

        $pages = ceil($total/$filter['pageSize']);
        $hasPreviousPage = $filter['pageNum'] > 1 ? true : false;
        $hasNextPage = $pages > $filter['pageNum'] ? true : false;
        $prePage = $hasPreviousPage ? $filter['pageNum'] - 1 : 1;
        $nextPage = $hasNextPage ? $filter['pageNum'] + 1 : $pages;

        $data = [
            'total' => $total,
            'list' => $list,
            'pages' => $pages,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'pageNum' => $filter['pageNum'],
        ];
        return success_json($data);
    }
}
