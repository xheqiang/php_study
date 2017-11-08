<?php

namespace App\Admin\Controllers;

use App\Models\Rental;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class RentalController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.rental'));
            $content->description(trans('admin::lang.list'));

            $content->body($this->grid());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Rental::class, function (Grid $grid) {
            //$grid->id('ID')->sortable();
            //$grid->title(trans('admin::lang.rental_title'));
            /*$grid->title(trans('admin::lang.rental_title'))->display(function ($title) {
                return "<a href='https://www.baidu.com' target='_blank'>$title</a>";
            });*/
            //拼接链接
            $grid->title(trans('admin::lang.rental_title'))->display(function () {
                return "<a href='$this->url' target='_blank'>$this->title</a>";
            });
            $grid->money(trans('admin::lang.rental_money'));
            $grid->room(trans('admin::lang.rental_room'));
            $grid->area(trans('admin::lang.rental_area'));
            $grid->method(trans('admin::lang.rental_method'));
            $grid->address(trans('admin::lang.rental_address'));
            $grid->line(trans('admin::lang.rental_line'));
            $grid->updated_at(trans('admin::lang.updated_at'));

            //禁用批量删除  $grid->disableBatchDeletion();
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                     $actions->disableDelete();  //禁用批量删除
                });
            });
            $grid->disableActions();   //不显示编辑删除
            $grid->disableCreation();  //不显示新建
            $grid->disableRowSelector();  //禁用checkoutbox

            //条件筛选查询
            $grid->filter(function ($filter) {
                $filter->like('title', trans('admin::lang.rental_title'));
                $filter->between('money', trans('admin::lang.rental_money'));
                $filter->like('address', trans('admin::lang.rental_address'));
                $filter->disableIdFilter();
            });
        });
    }
}
