<?php
namespace backend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Menu extends \yii\widgets\Menu{

    public $dropDownTemplate='
        <a class="has-arrow" href="#" aria-expanded="false">
            <i class="{class}"></i>
            <span>{label}</span>
        </a>
        {items}
    ';
    public $spanlabelTemplate='<li class="sidebar-header"><span>{label}</span></li>';
    public $submenuTemplate = "\n<ul class=\"nav-sub collapse\" aria-expanded=\"false\">\n{items}\n</ul>\n";
    public $linkSubTemplate = '<a href="{url}" class="has-arrow"><i class="{class}"></i><span>{label}</span></a>';

    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{class}' => isset($item['class'])?$item['class']:'',
                '{label}' => $item['label'],
            ]);
        } elseif($item['submenu']) {
            $template = ArrayHelper::getValue($item, 'dropdown_template', $this->dropDownTemplate);

            return strtr($template, [
                '{label}' => $item['label'],
                '{class}' => isset($item['class'])?$item['class']:'',
                '{items}' => $item['submenu'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'span_template', $this->spanlabelTemplate);

            return strtr($template, [
                '{label}' => $item['label']
            ]);
        }
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
//                if($parent_item) $parent_item['active']=$this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            /*Added submenu class and id*/
            $item['submenu'] ='';
            if (!empty($item['items'])) {
                $item['template']=$this->linkSubTemplate;

                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $submenuClass = ArrayHelper::getValue($item, 'submenuClass', '');
                if($item['active']) $submenuClass.=' in';
                $submenuId = ArrayHelper::getValue($item, 'submenuId', '');
                $item['submenu'] = strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                    '{class}' => $submenuClass,
                    '{id}' => $submenuId,
                ]);
                if(!isset($options['class'])) $options['class']='';
                $options['class'] .= ' nav-dropdown';
            }
            $menu = $this->renderItem($item);
            /*END change*/

            if ($tag === false) {
                $lines[] = $menu;
            } else {
                $lines[] = Html::tag($tag, $menu, $options);
            }
        }

        return implode("\n", $lines);
    }
}
