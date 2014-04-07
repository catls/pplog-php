<?php

class Pplog
{
    public $title;
    public $content;
    public $user_name;
    public $created_at;

    public $headers = array(
        'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
        'Accept-language: ja'
    );

    public function __construct($user_name='')
    {
        if ($user_name) {
            $this->getUserPoem($user_name);
        }
    }

    /**
     * ポエム文を取得
     *
     * @return string $text
     */
    public function getPoem()
    {
        $text = '';
        if($this->title) $text .= $this->title."\n";
        if ($this->content) {
            ($text)
                ? $text .= "---------------------------------------------\n$this->content\n"
                : $text .= $this->content."\n";
        }
        if($this->user_name) $text .= $this->user_name . " (" . $this->created_at.")\n";

        return $text;
    }

    /**
     * ユーザのポエムデータを取得
     *
     * @param  string $user_name 取得するユーザ名(先頭の@はどちらでも良い)
     * @return object $this
     */
    public function getUserPoem($user_name)
    {
        $user_name = preg_replace('/^@/','',$user_name);
        if (empty($user_name)) {
            return false;
        }
        $url = 'https://www.pplog.net/u/'.$user_name;

        $options = array(
            'http'=>array(
                'method'=>'GET',
                'header'=>join( "\r\n", $this->headers ),
            )
        );
        $html = file_get_contents($url,false,stream_context_create($options));
        $html = '<?xml version="1.0" encoding="UTF-8"?>'.$html;
        $xml = $this->_html2simplexml($this->_br2nl($html));

        $this->user_name = $this->_getUserName($xml);
        $this->title     = $this->_getTitle($xml);
        $this->content   = $this->_getContent($xml);
        $this->created_at = $this->_getCreatedAt($xml);

        return $this;
    }

    /**
     * 次のポエムデータを取得
     *
     * @return object $this
     */
    public function zapping()
    {
        $url = 'https://www.pplog.net/zapping';

        $options = array(
            'http'=>array(
                'method'=>'GET',
                'header'=>join( "\r\n", $this->headers ),
            )
        );
        $html = file_get_contents($url,false,stream_context_create($options));
        $html = '<?xml version="1.0" encoding="UTF-8"?>'.$html;
        $xml = $this->_html2simplexml($this->_br2nl($html));

        $this->user_name  = $this->_getUserName($xml);
        $this->title      = $this->_getTitle($xml);
        $this->content    = $this->_getContent($xml);
        $this->created_at = $this->_getCreatedAt($xml);

        return $this;
    }

    /**
     * XMLオブジェクトからユーザ名を取得する
     *
     * @access private
     * @param  object $SimpleXMLElement
     * @return string $user_name
     */
    private function _getUserName($xml)
    {
        $el = $xml->xpath('//div[@class="user-info"]//span[@class="user-name"]');
        $val = $el[0]->__toString();

        return $this->_closely($val);
    }

    /**
     * XMLオブジェクトからタイトルを取得する
     *
     * @access private
     * @param  object $SimpleXMLElement
     * @return string $title
     */
    private function _getTitle($xml)
    {
        $el = $xml->xpath('//section//div[@class="content"]/h1');
        $val = $el[0]->__toString();

        return $this->_closely($val);
    }

    /**
     * XMLオブジェクトからポエム内容を取得する
     *
     * @access private
     * @param  object $SimpleXMLElement
     * @return string $content
     */
    private function _getContent($xml)
    {
        $el = $xml->xpath('//section//div[@class="content-body"]');
        $val = $el[0]->__toString();

        return $this->_closely($val);
    }

    /**
     * XMLオブジェクトから投稿時間を取得する
     *
     * @access private
     * @param  object $SimpleXMLElement
     * @return string $content
     */
    private function _getCreatedAt($xml,$format = 'Y/m/d H:i:s')
    {
        $el = $xml->xpath('//section//div[@class="created-at"]');
        $val = $el[0]->__toString();
        $date = $this->_closely($val);

        return date($format,strtotime($date));
    }
    private function _closely($string)
    {
        $string  = preg_replace("/^\n+/",'',$string);
        $string  = preg_replace("/\n+$/",'',$string);

        return $string;
    }

    /**
     * htmlをsimpelXmlオブジェクトに変換する
     *
     * @access private
     * @param  string $html
     * @return object $SimpleXMLElement
     */
    private function _html2simplexml($html)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $ret = simplexml_import_dom($dom);

        return $ret;
    }

    /**
     * brタグを\nに変換
     *
     * @access private
     * @param  string $string
     * @return string $string
     */
    private function _br2nl($string)
    {
        return preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i', "\n", $string);
    }

}

