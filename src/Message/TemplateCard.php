<?php

namespace Huozi\WorkWechat\Message;

class TemplateCard extends Message
{

    const TYPE_TEXT = 'text_notice';
    const TYPE_IMAGE = 'news_notice';

    const CARD_ACTION_URL = 1;
    const CARD_ACTION_APP = 2;

    const HORIZONTAL_CONTENT_TEXT = 0;
    const HORIZONTAL_CONTENT_URL = 1;
    const HORIZONTAL_CONTENT_MEDIA = 2;

    const JUMP_NORMAL = 0;
    const JUMP_URL = 1;
    const JUMP_APP = 2;

    /**
     * @param string $cardType text_notice | news_notice
     */
    public function __construct($cardType = self::TYPE_TEXT)
    {
        $this->type = 'template_card';
        $this->cardType($cardType);
    }

    /**
     * 模版卡片的模版类型 文本卡片:text_notice 图文卡片:news_notice
     *
     * @param string $cardType
     */
    public function cardType($cardType)
    {
        $this->card_type = $cardType;
        return $this;
    }

    /**
     * 模版卡片的主要内容，包括一级标题和标题辅助信息
     *
     * @param string $title 一级标题，建议不超过26个字
     * @param string $desc 标题辅助信息，建议不超过30个字
     */
    public function mainTitle($title, $desc = '')
    {
        $this->main_title = compact('title', 'desc');
        return $this;
    }

    /**
     * 关键数据样式
     *
     * @param string $title 关键数据样式的数据内容，建议不超过10个字
     * @param string $desc 关键数据样式的数据描述内容，建议不超过15个字
     */
    public function emphasisContent($title, $desc)
    {
        $this->emphasis_content = compact('title', 'desc');
        return $this;
    }

    /**
     * 图片样式
     *
     * @param string $url 图片的url
     * @param float $aspect_ratio 图片的宽高比，宽高比要小于2.25，大于1.3，不填该参数默认1.3
     */
    public function cardImage($url, $aspect_ratio = null)
    {
        $this->card_type == static::TYPE_IMAGE AND $this->card_image = compact('url', 'aspect_ratio');
        return $this;
    }

    /**
     * 卡片二级垂直内容，该字段可为空数组，但有数据的话需确认对应字段是否必填，列表长度不超过4
     *
     * @param [type] $title
     * @param string $desc
     */
    public function verticalContent($title, $desc = '')
    {
        $this->message['vertical_content_list'][] = compact('title', 'desc');
        return $this;
    }

    /**
     * 卡片二级垂直内容，该字段可为空数组，但有数据的话需确认对应字段是否必填，列表长度不超过4
     *
     * @param array $list 内容数组
     */
    public function verticalContents(array $list)
    {
        $this->vertical_content_list = $list;
        return $this;
    }

    /**
     * 整体卡片的点击跳转事件，text_notice模版卡片中该字段为必填项
     *
     * @param int $type 卡片跳转类型，0或不填代表不是链接，1 代表跳转url，2 代表打开小程序。text_notice模版卡片中该字段取值范围为[1,2]
     * @param string $url 跳转事件的url，card_action.type是1时必填
     * @param string $appid 跳转事件的小程序的appid，card_action.type是2时必填
     * @param string $pagepath 跳转事件的小程序的pagepath，card_action.type是2时选填
     */
    public function cardAction(...$args)
    {
        $type = array_shift($args);
        if ($type == static::CARD_ACTION_URL) {
            list($url) = $args;
            $this->card_action = compact('type', 'url');
        } elseif ($type == static::CARD_ACTION_APP) {
            list($appid, $pagepath) = $args;
            $this->card_action = compact('type', 'appid', 'pagepath');
        } else {
            throw new \InvalidArgumentException(sprintf('type mast be %d or %d', static::CARD_ACTION_URL, static::CARD_ACTION_APP));
        }
        return $this;
    }

    /**
     * 卡片来源样式信息，不需要来源样式可不填写
     *
     * @param string $icon_url 来源图片的描述，建议不超过13个字
     * @param string $desc 模版卡片的主要内容，包括一级标题和标题辅助信息
     */
    public function source($icon_url, $desc = '')
    {
        $this->source = compact('icon_url', 'desc');
        return $this;
    }

    /**
     * 二级普通文本，建议不超过112个字
     *
     * @param string $title
     */
    public function subTitle($title)
    {
        $this->sub_title_text = $title;
        return $this;
    }

    /**
     * 添加二级标题+文本至列表，列表总长度不超过6
     *
     * @param int $type 链接类型，0或不填代表是普通文本，1 代表跳转url，2 代表下载附件
     * @param string $keyname 二级标题，建议不超过5个字
     * @param string $value 二级文本，如果horizontal_content_list.type是2，该字段代表文件名称（要包含文件类型），建议不超过26个字
     * @param string $url 链接跳转的url，horizontal_content_list.type是1时必填
     * @param string $media_id 附件的media_id，horizontal_content_list.type是2时必填
     */
    public function horizontalContent(...$args)
    {
        $type = array_shift($args);
        if ($type == static::HORIZONTAL_CONTENT_URL) {
            list($keyname, $value, $url) = $args;
            $this->message['horizontal_content_list'][] = compact('type', 'keyname', 'value', 'url');
        } elseif ($type == static::HORIZONTAL_CONTENT_MEDIA) {
            list($keyname, $value, $media_id) = $args;
            $this->message['horizontal_content_list'][] = compact('type', 'keyname', 'value', 'media_id');
        } else {
            $type = static::HORIZONTAL_CONTENT_TEXT;
            list($keyname, $value) = $args;
            $this->message['horizontal_content_list'][] = compact('type', 'keyname', 'value');
        }
        return $this;
    }

    /**
     * 二级标题+文本列表，该字段可为空数组，但有数据的话需确认对应字段是否必填，列表长度不超过6
     *
     * @param array $list 二级标题+文本列表
     */
    public function horizontalContents(array $list)
    {
        $this->horizontal_content_list = $list;
        return $this;
    }

    /**
     * 添加跳转指引样式至列表，列表长度不超过3
     *
     * @param int $type 跳转链接类型，0或不填代表不是链接，1 代表跳转url，2 代表跳转小程序
     * @param string $title 跳转链接样式的文案内容，建议不超过13个字
     * @param string $url 跳转链接的url，jump_list.type是1时必填
     * @param string $appid 跳转链接的小程序的appid，jump_list.type是2时必填
     * @param string $pagepath 跳转链接的小程序的pagepath，jump_list.type是2时选填
     */
    public function jump(...$args)
    {
        $type = array_shift($args);
        if ($type == static::JUMP_URL) {
            list($title, $url) = $args;
            $this->message['jump_list'][] = compact('type', 'title', 'url');
        } elseif ($type == static::JUMP_APP) {
            list($title, $appid, $pagepath) = $args;
            $this->message['jump_list'][] = compact('type', 'title', 'appid', 'pagepath');
        } else {
            $type = static::JUMP_NORMAL;
            list($title) = $args;
            $this->message['jump_list'][] = compact('type', 'title');
        }
        return $this;
    }

    /**
     * 跳转指引样式的列表，该字段可为空数组，但有数据的话需确认对应字段是否必填，列表长度不超过3
     *
     * @param array $list 跳转列表
     */
    public function jumps(array $list)
    {
        $this->jump_list = $list;
        return $this;
    }

}
