<?php

class Badge
{

    private $text;
    private $type = "primary";
    private $tag = "span";

    public function text($text): Badge
    {
        $this->text = translate($text);
        return $this;
    }

    public function success(): Badge
    {
        $this->type = "success";
        return $this;
    }

    public function danger(): Badge
    {
        $this->type = "danger";
        return $this;
    }

    public function info(): Badge
    {
        $this->type = "info";
        return $this;
    }

    public function warning(): Badge
    {
        $this->type = "warning";
        return $this;
    }

    public function tag($tag): Badge
    {
        $this->tag = $tag;
        return $this;
    }

    private function type2class(): string
    {
        switch ($this->type) {
            case "success":
                $class = "bg-success";
                break;
            case "danger":
                $class = "bg-danger";
                break;
            case "warning":
                $class = "bg-warning";
                break;
            case "info":
                $class = "bg-info";
                break;
            default:
                $class = "bg-primary";
        }
        return $class;
    }

    public function uppsercase(): Badge
    {
        $text = new Text();
        $this->text = $text->set($this->text)->uppercase()->output();
        return $this;
    }

    public function output(): string
    {
        $additional_class = "";
        $tag = $this->tag;
        $type = $this->type2class();
        $text = $this->text;
        return "<" . $tag . " class=\"item-badge badge " . $additional_class . " " . $type . "\">" . $text . "</" . $tag . ">";
    }

}