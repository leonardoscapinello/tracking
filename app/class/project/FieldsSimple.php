<?php

class FieldsSimple
{

    private $label;
    private $name;
    private $id;
    private $required = "";
    private $disabled = "";
    private $readonly = "";

    private function hashString($string): string{
        return md5($string);
    }

    public function hash(): FieldsSimple
    {
        $this->name = not_empty_bool($this->name) ?  $this->hashString($this->name) : "";
        $this->id = not_empty_bool($this->id) ?  $this->hashString($this->id) : "";
        return $this;
    }

    public function label($label): FieldsSimple
    {
        $this->label = $label;
        return $this;
    }

    public function name($name): Fields
    {
        $this->name = $name;
        return $this;
    }

    public function id($id): Fields
    {
        $this->id = $id;
        return $this;
    }

    public function required(): Fields
    {
        $this->required = "required=\"required\"";
        return $this;
    }


    public function readonly()
    {
        $this->required = "readonly=\"readonly\"";
        return $this;
    }


    public function disabled()
    {
        $this->required = "disabled=\"disabled\"";
        return $this;
    }

    public function text(): ?string
    {

        if (!not_empty($this->name)) return null;
        if (!not_empty($this->id)) $this->id($this->name);
        $result = "<div class=\"form-field\">";
        $result .= "<label for=\"password\">" . translate($this->label) . "</label >";
        $result .= "<input type=\"text\" name=\"" . $this->name . "\" id=\"" . $this->id . "\" class=\"" . $this->name . "\" " . $this->required . " " . $this->disabled . " " . $this->readonly . " />";
        $result .= "</div>";
        $this->clean();
        return $result;

    }

    private function clean()
    {
        $this->label = "";
        $this->name = "";
        $this->id = "";
        $this->required = "";
        $this->disabled = "";
        $this->readonly = "";
    }

}