@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 添加商品
    </div>
    <!--面包屑导航 结束-->

    <!--结果页快捷搜索框 开始-->
    <div class="search_wrap">
        <form action="" method="post">
            <table class="search_tab">
                <tr>
                    <th width="120">选择分类:</th>
                    <td>
                        <select onchange="javascript:location.href=this.value;">
                            <option value="">全部</option>
                            <option value="http://www.baidu.com">百度</option>
                            <option value="http://www.sina.com">新浪</option>
                        </select>
                    </td>
                    <th width="70">关键字:</th>
                    <td><input type="text" name="keywords" placeholder="关键字"></td>
                    <td><input type="submit" name="sub" value="查询"></td>
                </tr>
            </table>
        </form>
    </div>
    <!--结果页快捷搜索框 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="#"><i class="fa fa-plus"></i>新增文章</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th>标题</th>
                        <th>审核状态</th>
                        <th>点击</th>
                        <th>发布人</th>
                        <th>更新时间</th>
                        <th>评论</th>
                        <th width="8%;">操作</th>
                    </tr>
                    <tr>
                        <td class="tc">59</td>
                        <td>
                            <a href="#">Apple iPhone 6 Plus (A1524) 16GB 金色 移动联通电信4G手机</a>
                        </td>
                        <td>0</td>
                        <td>2</td>
                        <td>admin</td>
                        <td>2014-03-15 21:11:01</td>
                        <td></td>
                        <td>
                            <a href="#">修改</a>
                            <a href="#">删除</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="tc">59</td>
                        <td>
                            <a href="#">三星 SM-G5308W 白色 移动4G手机 双卡双待</a>
                        </td>
                        <td>0</td>
                        <td>2</td>
                        <td>admin</td>
                        <td>2014-03-15 21:11:01</td>
                        <td></td>
                        <td>
                            <a href="#">修改</a>
                            <a href="#">删除</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="tc">59</td>
                        <td>
                            <a href="#">荣耀 6 (H60-L11) 3GB内存增强版 白色 移动4G手机</a>
                        </td>
                        <td>0</td>
                        <td>2</td>
                        <td>admin</td>
                        <td>2014-03-15 21:11:01</td>
                        <td></td>
                        <td>
                            <a href="#">修改</a>
                            <a href="#">删除</a>
                        </td>
                    </tr>
                </table>

                <div class="page_nav">
                    <div>
                        <a class="first" href="/wysls/index.php/Admin/Tag/index/p/1.html">&lt;&lt;</a>
                        <a class="prev" href="/wysls/index.php/Admin/Tag/index/p/7.html">&lt;</a>
                        <a class="num" href="/wysls/index.php/Admin/Tag/index/p/6.html">6</a>
                        <a class="num" href="/wysls/index.php/Admin/Tag/index/p/7.html">7</a>
                        <span class="current">8</span>
                        <a class="num" href="/wysls/index.php/Admin/Tag/index/p/9.html">9</a>
                        <a class="num" href="/wysls/index.php/Admin/Tag/index/p/10.html">10</a>
                        <a class="next" href="/wysls/index.php/Admin/Tag/index/p/9.html">&gt;</a>
                        <a class="end" href="/wysls/index.php/Admin/Tag/index/p/11.html">&gt;&gt;</a>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
@endsection
