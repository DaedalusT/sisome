<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// +----------------------------------------------------------------------
// | SISOME 标签列表
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.sisome.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 绛木子 <master@lixianhua.com>
// +----------------------------------------------------------------------

class Widget_Metas_List extends Widget_Abstract_Metas
{
    /**
     * 入口函数
     *
     * @access public
     * @return void
     */
    public function execute()
    {
        $this->parameter->setDefault(array(
                'sort' => 'count',
                'ignoreZeroCount' => false,
                'desc' => true,
                'limit' => 0,
                'type' =>'',
            )
        );
        $select = $this->select()->order($this->parameter->sort,
        $this->parameter->desc ? Typecho_Db::SORT_DESC : Typecho_Db::SORT_ASC);

        if($this->parameter->type && in_array($this->parameter->type, array('tag','category'))){
            $select->where('type = ?', $this->parameter->type);
        }
        /** 忽略零数量 */
        if ($this->parameter->ignoreZeroCount) {
            $select->where('count > 0');
        }

        /** 总数限制 */
        if ($this->parameter->limit) {
            $select->limit($this->parameter->limit);
        }
        
        /** 父级分类 */
        if ($this->parameter->parent) {
            $select->where('parent = ?', $this->parameter->parent);
        }
        $this->db->fetchAll($select, array($this, 'push'));
    }

    /**
     * 按分割数输出字符串
     *
     * @access public
     * @param string $param 需要输出的值
     * @return void
     */
    public function split()
    {
        $args = func_get_args();
        array_unshift($args, $this->count);
        echo call_user_func_array(array('Typecho_Common', 'splitByCount'), $args);
    }
}