<?php

namespace App\Admin\Controllers;

use App\Models\House;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class HouseController extends Controller
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

            $content->header(trans('admin::lang.house'));
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
        return Admin::grid(House::class, function (Grid $grid) {
            //拼接链接
            $grid->title(trans('admin::lang.house_title'))->display(function () {
                return "<a href='$this->url' target='_blank'>$this->title</a>";
            });
            $grid->total_price(trans('admin::lang.house_total_price'))->display(function () {
                return $this->total_price . 'W';
            });
            $grid->unit_price(trans('admin::lang.house_unit_price'))->display(function () {
                return $this->unit_price . '元/㎡';
            });
            $grid->acreage(trans('admin::lang.house_acreage'));
            $grid->house_type(trans('admin::lang.house_type'));
            $grid->area(trans('admin::lang.houre_address'))->display(function () {
                return $this->area . "•" . $this->region . "•" . $this->village; 
            });
            $grid->floor(trans('admin::lang.house_floor'));
            $grid->orientation(trans('admin::lang.house_orientation'));
            $grid->renovation(trans('admin::lang.house_renovation'));
            $grid->elevator(trans('admin::lang.house_elevator'));
            $grid->floor_scale(trans('admin::lang.house_floor_scale'));
            $grid->property_right(trans('admin::lang.house_property_right'));
            $grid->listing_time(trans('admin::lang.house_listing_time'));
            $grid->house_age(trans('admin::lang.house_other_info'))->display(function () {
                $display = '';
                /*$display = trans('admin::lang.house_heating') .  ":" . $this->heating . "<br>";
                $display .= trans('admin::lang.house_building_type') .  ":" . $this->building_type . "<br>";
                $display .= trans('admin::lang.house_purpose') .  ":" . $this->house_purpose . "<br>";*/
                $display .= trans('admin::lang.house_age') . ":" . $this->house_age . "<br>";
                $display .= trans('admin::lang.house_mortgage') .  ":" . $this->mortgage . "<br>";
                return $display;
            });

            //禁用批量删除  $grid->disableBatchDeletion();
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                     $actions->disableDelete();  //禁用批量删除
                });
            });
            $grid->disableExport(); //禁用导出按钮
            $grid->disableActions();   //不显示编辑删除
            $grid->disableCreation();  //不显示新建
            $grid->disableRowSelector();  //禁用checkoutbox

            //条件筛选查询
            $grid->filter(function ($filter) {
                $filter->equal('id');   //需要加上改字段，否则导致搜索后第一个字段为空
                $area_option = [
                    '和平' => '和平',
                    '河西' => '河西',
                    '南开' => '南开',
                    '河东' => '河东',
                    '河北' => '河北',
                    '红桥' => '红桥',
                ];
                $filter->equal('area', trans('admin::lang.house_area'))->select($area_option);
                $filter->where(function ($query) {
                    $query->where('title', 'like', "%{$this->input}%")
                        ->orWhere('village', 'like', "%{$this->input}%");
                }, trans('admin::lang.house_title_village'));
                $filter->between('total_price', trans('admin::lang.house_total_price'));
                $elevator_option = [
                    '有' => '有',
                    '无' => '无',
                    '暂无数据' => '暂无数据',
                ];
                $filter->equal('elevator', trans('admin::lang.house_elevator'))->select($elevator_option);
                $filter->disableIdFilter();
            });
        });
    }
}
