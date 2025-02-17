<?php
/**
 * file Page.class.php  分页类
 * @author   WangJian
 */

namespace App\Tool\Page;

class Page {
    /* 总记录数 */
    private $total;      
    
    /* 每页显示的记录数 */
    private $countPerPage;
    
    /* 查询数据中的偏移量 */
    private $offset;
    
    /* 总页数 */
    private $pages;
    
    /* 当前页面 */
    private $curPage;
    
    /* 页面样式配置 */
    private $configs = array(
        'total' => 'total?row',                 //?为占位符
        'pages' => 'total?page',               //页数信息，?为占位符
        'prev' => 'prev',
        'next' => 'next',
        'first' => 'first',
        'last' => 'last',
        'goPageText' => 'Go',                       //页面跳转按钮文本
        'goPageSize' => 3,                            //页面跳转文本框大小
        'pageNum' => 5                      //显示的最大页码个数, 默认为9个
    );
    
    
    /**
     * 构造函数
     * @param int $total    总记录数
     * @param int $countPerPage     每页显示数目, 默认为5条
     * @param array $configs   页面样式配置信息
     * @return void 
     */
    public function __construct($total, $countPerPage = 5, $configs = array()) {
        /* 类属性的初始化 */
        $this->total = intval($total);
        $this->countPerPage = $countPerPage;
        $this->configs = array_merge($this->configs, $configs);
        $this->pages = ceil($this->total / $this->countPerPage);
        $this->curPage = @$_GET['page'];
        $this->curPage || $this->curPage = 1;
        $this->curPage = ($this->curPage < 1 ? 1 : $this->curPage);
        $this->curPage = ($this->curPage > $this->pages ? $this->pages : $this->curPage);
        $this->offset = $this->start();
    }
    
    
    /**
     * 返回offset信息
     * @return string;
     */
    public function getOffset() {
        return $this->offset;
    }
    
    /**
     * 输出页面样式结构信息
     * @return string  返回页面样式结构信息
     */
    public function fpage($user=false) {
        $pageinfo = '';
        
        /* 首页 */
        $pageinfo .= $this->firstPage();
        
        /* 上一页 */
        $pageinfo .= $this->prevPage();
        
        /* 页码列表 */
        $pageinfo .= $this->pageList();
        
        /* 下一页 */
        $pageinfo .= $this->nextPage();
        
        /* 末页 */
        $pageinfo .= $this->lastPage();
        
        /* 记录总数 */
        //$pageinfo .= $this->total();
        
        /* 页面总数 */
        //$pageinfo .= $this->pages();
        
        /* 页面跳转 */
        if(!$user){
            $pageinfo .= $this->goPage();
        }
        
        return $pageinfo;
    }
  
    
    /**
     * 计算当前页面的起始记录数
     * @return int  
     */
    private function start() {
        return ($this->curPage - 1)*$this->countPerPage;
    }
    
    
    /**
     * 输出"首页"
     * @return string
     */
    private function firstPage() {
        /* 如果当前页是首页，则不显示 */
        return $this->curPage <= 1 ? '' : '<a href="'.self::urlPage(1).'" class="firstPage">'.$this->configs['first'].'</a>';
    }
    
    
    /**
     * 输出"上一页"
     * @return string
     */
    private function prevPage() {
        /* 如果当前页是首页，则不显示 */
        return $this->curPage <= 1 ? '' : '<a href="'.self::urlPage($this->curPage-1).'" class="prevPage">'.$this->configs['prev'].'</a>';
    }
    
    
    /**
     * 输出"下一页"
     * @return string
     */
    private function nextPage() {
        /* 如果当前页是末页，则不显示 */
        return $this->curPage >= $this->pages ? '' : '<a href="'.self::urlPage($this->curPage+1).'" class="nextPage">'.$this->configs['next'].'</a>';
    }
    
    
    /**
     * 输出"末页"
     * @return string
     */
    private  function lastPage() {
        /* 如果当前页是末页，则不显示 */
        return $this->curPage >= $this->pages ? '' : '<a href="'.self::urlPage($this->pages).'" class="lastPage">'.$this->configs['last'].'</a>';
    }
    
    
    /**
     * 输出"页码列表"
     * @return string
     */
    private function pageList() {
        /* 显示的起始、终止页码 */
        if($this->pages <= $this->configs['pageNum']) {
            $startPage = 1;
            $endPage = $this->pages;
        } else {
            if($this->curPage <= ceil($this->configs['pageNum']/2)) {
                $startPage = 1;
                $endPage = $this->configs['pageNum'];
            } else if($this->curPage >= $this->pages - floor($this->configs['pageNum']/2)) {
                $startPage = $this->pages - $this->configs['pageNum'] + 1;
                $endPage = $this->pages;
            } else {
                $startPage = $this->curPage - floor($this->configs['pageNum']/2);
                $endPage = $this->curPage + floor($this->configs['pageNum']/2);
            }
        }
        
        $str = '';
        for($i = $startPage; $i <= $endPage; $i++) {
            if($i == $this->curPage) {
                $str .= '<a href="'.self::urlPage($i).'" class="pageItem curPage">'.$i.'</a>';
            } else {
                $str .=  '<a href="'.self::urlPage($i).'" class="pageItem">'.$i.'</a>';
            }
        }
        
        return $str;
    }
    
    
    /**
     * 输出"记录总数"
     * @return string;
     */
    private function total() {
        /* 替换占位符后返回 */
        $replacement = '<span class="total totalNumber">'.$this->total.'</span>';
        return str_replace('?', $replacement, '<span class="total">'.$this->configs['total'].'</span>');
    }
    
    
    /**
     * 输出"总页数"
     * @return string
     */
    private function pages() {
        /* 替换占位符后返回 */
        $replacement = '<span class="pages pagesNumber">'.$this->pages.'</span>';
        return str_replace('?', $replacement, '<span class="pages">'.$this->configs['pages'].'</span>');
    }
    
    
    /**
     * 输出"页面跳转"
     * @return string
     */
    private function goPage() {
        /* 构造文本框 */
        $input = "<input id='goPageText' class='goPageText' onkeydown='javascript:if(event.keyCode == 13 && this.value != \"\") {var url = window.location.href; if(url.indexOf(\"?\") == -1) url += \"?page=\" + this.value; else {var pattern=/page=[0-9]*(.*)/; pattern.test(url) ? url = url.replace(pattern, \"page=\" + this.value + \"$1\") : url += \"&page=\" + this.value; } window.location.href=url;}' onkeyup='javascript:var pattern=/^[0-9]*$/; if(pattern.test(this.value) == false) this.value = this.value.substr(0, this.value.length-1);' size='".$this->configs['goPageSize']."' />";
        
        /* 构造跳转按钮 */
        $link = "<a href='javascript:if(this.value != \"\") {var pageText = document.getElementById(\"goPageText\"); var url = window.location.href; if(url.indexOf(\"?\") == -1) url += \"?page=\" + pageText.value; else {var pattern=/page=[0-9]*(.*)/; pattern.test(url) ? url = url.replace(pattern, \"page=\" + pageText.value + \"$1\") : url += \"&page=\" + pageText.value; } window.location.href=url;}' class='goPageLink'>".$this->configs['goPageText']."</a>";
        
        return $input.$link;
    }
    
    
    /**
     * 在当前url地址加上page参数
     * @param int $page             //页码
     * @return string
     */
    private static function urlPage($page) {
        /* 获取当前完整URL地址 */
        $url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
        
        /* 如果URL地址中无参数，则直接在后面添加?page=$page */
        if(!strpos($url, '?')) {
            $url .= '?page='.$page;
        } else {
            /* url中含有参数
             * 判断是否含有page参数，如果没有，在后面添加，如果有了则进行替换
             */
            $pattern = '/page=\d*(.*)/';
            preg_match($pattern, $url) ? $url = preg_replace($pattern, 'page='.$page.'$1', $url) : $url .= '&page='.$page;
        }
        
        return $url;
    }
}