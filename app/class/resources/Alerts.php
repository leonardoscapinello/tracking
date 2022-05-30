<?php

class Alerts
{

    private $_class;
    private $_text;

    public function danger(): Alerts
    {
        $this->_class = "notify-base notify-danger shake";
        return $this;
    }

    public function success(): Alerts
    {
        $this->_class = "notify-base notify-success";
        return $this;
    }

    public function warning(): Alerts
    {
        $this->_class = "notify-base notify-warning";
        return $this;
    }

    public function text($string): Alerts
    {
        $this->_text = $string;
        return $this;
    }

    public function display()
    {
        $result = '<div class="notify-container ' . str_replace("notify-base", "", $this->_class) . '">';
        $result .= '    <div class="' . $this->_class . '">';
        $result .= '        <div class="spc-icon"></div>';
        $result .= '        <div class="spc-text">';
        $result .= '            <p>' . translate($this->_text) . '</p>';
        $result .= '        </div>';
        $result .= '    </div>';
        $result .= '</div>';
        return $result;
    }


}