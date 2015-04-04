<?php
/**
 * JPagination 
 * 分页类 
 */
class JPagination
{
    /**
     * count 
     * 总条数
     * @var mixed
     */
    public $count;
    /**
     * total 
     * 总页数
     * @var mixed
     */
    public $total;
    /**
     * current 
     * 当前页
     * @var mixed
     */
    public $current;
    /**
     * previous 
     * 上一页
     * @var mixed
     */
    public $previous;
    /**
     * next 
     * 下一页
     * @var mixed
     */
    public $next;
    /**
     * first
     * 首页
     * @var mixed
     */
    public $first;
    /**
     * last
     * 尾页
     * @var mixed
     */
    public $last;
    /**
     * size 
     * 每页条数
     * @var mixed
     */
    public $size;
    /**
     * baseurl
     * @var mixed
     */
    public $baseurl;
    /**
     * select 
     * 下拉框链接
     * @var mixed
     */
    public $select;
    private $_selectType;
    private $_maxSize = 50;
    private $_minSize ;
    private $_param;
	
    public function __construct($count)
    {/*{{{*/
        $this->count    = intval($count);
        $this->current  = 1;
        $this->size     = PAGE_SIZE;
        $this->_minSize     = 10;
        $this->previous = false;
        $this->next     = false;
        $this->first     = false;
        $this->last     = false;
        $this->baseurl    = '';
        $this->_selectType = array($this->size, 30, 50);
        $this->_param = array();
    }/*}}}*/
    /**
     * setPageSize 
     * 设置每页条数
     * @param mixed $size 
     * @return void
     */
    public function setPageSize($size)
    {/*{{{*/
        $size = intval($size);
        if ($size > $this->_maxSize)
        {
            $this->size = $this->_maxSize;
        }
        else if ($size < $this->_minSize)
        {
            $this->size = $this->_minSize;
        }
        else
        {
            $this->size = $size;
        }
    }/*}}}*/
    /**
     * setCurrent 
     * 设置当前页
     * @param mixed $current 
     * @return void
     */
    public function setCurrent($current)
    {/*{{{*/
        $current     = intval($current);
        $pages       = $this->_getPages();
        $this->total = $pages;
        if ($current < 1)
        {
            $this->current = 1;
        }
        else if ($current > $this->total)
        {
            $this->current = $this->total;
            $baseUrl   = $this->_getBaseUrl();
            Yii::app()->request->redirect($baseUrl . "&page=" . $this->current);
        }
        else
        {
            $this->current = $current;
        }
    }

    /**
     * makePages 
     * 生成分页
     * @return void
     */
    public function makePages()
    {/*{{{*/
        $this->_setPreviousUrl();
        $this->_setNextUrl();
        $this->_setSelectUrl();
        $this->_setFirstPageUrl();
        $this->_setLastPageUrl();
        $this->_setBaseUrl();
    }/*}}}*/
    /**
     * getStart 
     * 获取起始位置
     * @return void
     */
    public function getStart()
    {/*{{{*/
        $start = ($this->current - 1) * $this->size;
        return ($this->current - 1) * $this->size;
    }/*}}}*/
    /**
     * getPageSize 
     * 获取每页条数
     * @return void
     */
    public function getPageSize()
    {
        return $this->size;
    }
    public function setParam ($param) {
        $this->_param = $param;
    }
    /**
     * getPageSize 
     * 获取每页条数
     * @return void
     */
    private function _setBaseUrl()
    {
        $this->baseurl = $this->_getBaseUrl();
    }
    /**
     * _getPages 
     * 获取分页数
     * @return void
     */
    private function _getPages()
    {/*{{{*/
        $pages = intval(ceil($this->count / $this->size));
        $pages = $pages ? $pages : 1;
        return $pages;
    }/*}}}*/
    /**
     * _hasPrevious 
     * 是否有上一页
     * @return void
     */
    private function _hasPrevious()
    {/*{{{*/
        if ($this->current < 2)
        {
            return false;
        }
        return true;
    }/*}}}*/
    /**
     * _hasNext 
     * 是否有下一页
     * @return void
     */
    private function _hasNext()
    {/*{{{*/
        if ($this->current > ($this->total -1))
        {
            return false;
        }
        return true;
    }/*}}}*/
    /**
     * _setNextUrl 
     * 设置下一页的url地址
     * @return void
     */
    private function _setNextUrl()
    {/*{{{*/
        if ($this->_hasNext())
        {
            $baseUrl = $this->_getBaseUrl();
            $page    = intval($this->current + 1);
            $url     = $baseUrl . '&page=' . $page ;
            /*if (count($this->_param) > 0) {
                foreach ($this->_param as $key => $val) {
                    $url .= '&'.$key.'=' . $val;
                }
            }*/
            $this->next = $url;
        }
    }/*}}}*/
    /**
     * _setPreviousUrl 
     * 设置上一页的url地址
     * @return void
     */
    private function _setPreviousUrl()
    {/*{{{*/
        if ($this->_hasPrevious())
        {
            $baseUrl = $this->_getBaseUrl();
            $page    = intval($this->current - 1);
            $url     = $baseUrl . '&page=' . $page;
            /*if (count($this->_param) > 0) {
                foreach ($this->_param as $key => $val) {
                    $url .= '&'.$key.'=' . $val;
                }
            }*/
            $this->previous = $url;
        }
    }/*}}}*/
    /**
     * _setSelectUrl 
     * 设置每页条数下拉框的地址
     * @return void
     */
    private function _setSelectUrl()
    {/*{{{*/
        $selectUrl = array();
        $baseUrl   = $this->_getBaseUrl();
        foreach ($this->_selectType as $type)
        {
            $selectUrl[$type] = $baseUrl . '&page=' . $this->current;
        }
        $this->select = $selectUrl;
    }/*}}}*/
    /**
     * _getBaseUrl 
     * 获取base url
     * @return void
     */
    private function _getBaseUrl()
    {/*{{{*/
        $uri      = $_SERVER['REQUEST_URI'];
        $uriArray = parse_url($uri);
        $baseUrl  = htmlspecialchars($uriArray['path']) . '?';
        $suffix   = '';
        foreach ($_GET as $key => $val)
        {
            $key = htmlspecialchars($key);
            if ($key == 'page')
            {
                continue;
            }
            if (! is_array($val))
            {
                $val     = urlencode($val);
                $suffix .= "&{$key}={$val}";
                continue;
            }
            foreach($val as $arrayKey=>$arrayVal)
            {
                $arrayVal  = urlencode($arrayVal);
                $suffix   .= "&{$key}[$arrayKey]={$arrayVal}";
            }
        }
        if ($suffix)
        {
            $suffix = substr($suffix, 1);
        }
        $baseUrl .= $suffix;
        if (count($this->_param) > 0) {
            foreach ($this->_param as $val) {
                $baseUrl .= '&'.$val['key'].'=' . $val['val'];
            }
        }
        return $baseUrl;
    }/*}}}*/
    /**
     * _setFirstPage
     * 设置首页的url地址
     * @return void
     */
    private function _setFirstPageUrl()
    {
    	if($this->current == 1){
    		$this->first = false;
    	}else{
	        $baseUrl = $this->_getBaseUrl();
	        $page    = 1;
	        $url     = $baseUrl . '&page=' . $page ;
	        $this->first = $url;
    	}
    }
    /**
     * _setLastPage
     * 设置尾页的url地址
     * @return void
     */
    private function _setLastPageUrl()
    {
    	if($this->current == $this->total){
    		$this->last = false;
    	}else{
	        $baseUrl = $this->_getBaseUrl();
	        $page    = $this->total;
	        $url     = $baseUrl . '&page=' . $page;
	        $this->last = $url;
    	}
    }
}
