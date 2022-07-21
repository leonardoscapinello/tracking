<?php

/*<div class="switch-button">
    <input id="ipt" tabindex="0" type="checkbox">
    <label for="ipt" class="toggle" tabindex="0" title="">
        <span class="toggle-handler"></span>
    </label>
</div>-->*/

class Fields
{

    private $_required = false;
    private $_disabled = false;
    private $_readonly = false;
    private $_label;
    private $_class = "spc-input form-control";
    private $_labelclass = "";
    private $_style;
    private $_blur;
    private $_icon;
    private $_markdown;
    private $_template;
    private $_options = [];
    private $_value;
    private $_placeholder;
    private $_id;
    private $_name;
    private $_selected;
    private $_field_type = "text";
    private $_custom_type = "text";
    private $_custom_script = "";
    private $_maxlength;
    private $_min;
    private $_max;
    private $_options_html;

    public function __construct()
    {

    }

    public function text(string $custom_type = "text"): Fields
    {
        $this->_custom_type = $custom_type;
        $this->_template = '<div class="form-group fv-row mb-10"><label for="%for" class="fw-semibold fs-6 mb-2 %labelclass" %markdown >%label</label><input type="' . $custom_type . '" class="form-control form-control-solid mb-3 mb-lg-0 %class" name="%name" %id placeholder="%placeholder" value="%value" %min %max %maxlength %blur %style %required %disabled %readonly %customScript/></div>';
        return $this;
    }


    public function hidden(): Fields
    {
        $this->_field_type = "hidden";
        $this->_template = '<input type="hidden" name="%name" %id value="%value">';
        return $this;
    }

    public function textarea(): Fields
    {
        $this->_field_type = "textarea";
        $this->_template = '<div class="form-group fv-row mb-10"><label for="%for" class="%labelclass"><span class="spc-label-text">%label</span></label><textarea class="form-control form-control-solid mb-3 mb-lg-0 %class" name="%name" %id placeholder="%placeholder" %maxlength %blur %style %required %disabled %readonly>%value</textarea></div>';
        return $this;
    }

    public function button(): Fields
    {
        $this->_field_type = "button";
        $this->_class = str_replace("spc-input", "menu button primary-small", $this->_class);
        $this->_template = '<div class="%labelclass"><button class="btn %class" %style value="%value" %customScript>%icon %value</button></div>';
        return $this;
    }


    public function select(): Fields
    {
        $this->_field_type = "select";
        $this->_class = str_replace("spc-input", "spc-select", $this->_class);
        $this->_template = '<div class="fv-row mb-10"><label for="%id" class="fw-semibold fs-6 mb-2 %labelclass"><span class="spc-label-text">%label</span>%markdown</label><select class="form-select form-select %class" name="%name" %id  %style %required %disabled %customScript>%options</select></div>';
        return $this;
    }

    public function checkbox(): Fields
    {
        $this->_field_type = "checkbox";
        $this->_class = str_replace("spc-input", "switch-button", $this->_class);
        $this->_template = '<label class="switch-box %labelclass" %style><div class="switch-button"><input %id name="%name" value="%value" tabindex="0" type="checkbox" class="%class" %required %disabled %selected><label for="%id" class="toggle" tabindex="0" title=""><span class="toggle-handler"></span></label></div><p>%label</p><b>%markdown</b></label>';
        return $this;
    }


    public function classList($class): Fields
    {
        $this->_class .= " " . $class;
        if ($this->_field_type === "button") {
            $this->_class = "menu button " . $class;
        }
        return $this;
    }


    public function icon($icon, $style): Fields
    {
        $icons = new StaticFilesIcons();
        $this->_icon = $icons->icon($icon)->style($style)->output("i");
        return $this;
    }


    public function labelClass($class): Fields
    {
        $this->_labelclass .= " " . $class;
        return $this;
    }

    public function name($name): Fields
    {
        $this->_name = $name;
        return $this;
    }

    public function maxlength(int $length): Fields
    {
        $this->_maxlength = $length;
        return $this;
    }


    public function min(int $min): Fields
    {
        $this->_min = $min;
        return $this;
    }


    public function max(int $max): Fields
    {
        $this->_max = $max;
        return $this;
    }

    public function id($id): Fields
    {
        $this->_id = $id;
        return $this;
    }

    public function value($value): Fields
    {
        $this->_value = ($value);
        return $this;
    }

    public function options(array $options): Fields
    {
        $this->_options = $options;
        return $this;
    }

    public function markdown($markdown): Fields
    {
        $this->_markdown = translate($markdown);
        return $this;
    }

    public function label($label): Fields
    {
        $this->_label = translate($label);
        return $this;
    }

    public function placeholder($placeholder): Fields
    {
        $this->_placeholder = translate($placeholder);
        return $this;
    }

    public function required(): Fields
    {
        $this->_required = "required=\"required\"";
        return $this;
    }

    /* TOGGLE READONLY BY OUTSIDE PARAM */
    public function readonly($readonly = true): Fields
    {
        if ($readonly) $this->_readonly = "readonly=\"readonly\"";
        return $this;
    }

    public function disabled(): Fields
    {
        $this->_required = "";
        $this->_disabled = "disabled=\"disabled\"";
        return $this;
    }


    public function selected($value): Fields
    {
        $text = new Text();
        if ($text->equalsIgnoreCase($this->_value, $value)) $this->_selected = "checked=\"checked\"";
        return $this;
    }


    public function style($stylesheet): Fields
    {
        $this->_style = $stylesheet;
        return $this;
    }


    public function customScript($customScript): Fields
    {
        $this->_custom_script = $customScript;
        return $this;
    }


    public function blur($blur): Fields
    {
        $this->_blur = $blur;
        return $this;
    }


    public function output(): string
    {
        $output = $this->_template;

        $this->optionsHTML();

        if ($this->_required) $this->_labelclass .= " requried";

        $output = str_replace("%class", $this->_class, $output);
        $output = str_replace("%labelclass", $this->_labelclass, $output);
        $output = str_replace("%name", $this->_name, $output);

        if (not_empty($this->_id)) $output = str_replace("%for", $this->_id, $output);
        if (not_empty($this->_id)) $output = str_replace("%id", "id=\"" . $this->_id . "\"", $output);

        $output = str_replace("%label", $this->_label, $output);
        $output = str_replace("%markdown", (not_empty_bool($this->_markdown) ? ("data-label-help=\"after\" data-bs-toggle=\"tooltip\" data-bs-custom-class=\"tooltip-inverse\" data-bs-placement=\"top\" title=\"" . $this->_markdown . "\"") : ""), $output);
        $output = str_replace("%required", (not_empty_bool($this->_required) ? $this->_required : ""), $output);
        $output = str_replace("%disabled", (not_empty_bool($this->_disabled) ? $this->_disabled : ""), $output);
        $output = str_replace("%readonly", (not_empty_bool($this->_readonly) ? $this->_readonly : ""), $output);
        $output = str_replace("%placeholder", (not_empty_bool($this->_placeholder) ? $this->_placeholder : ""), $output);
        $output = str_replace("%maxlength", (not_empty_bool($this->_maxlength) ? "maxlength=\"" . $this->_maxlength . "\"" : ""), $output);
        $output = str_replace("%min", (not_empty_bool($this->_min) ? "min=\"" . $this->_min . "\"" : ""), $output);
        $output = str_replace("%max", (not_empty_bool($this->_max) ? "max=\"" . $this->_max . "\"" : ""), $output);
        $output = str_replace("%customScript", (not_empty_bool($this->_custom_script) ? $this->_custom_script : ""), $output);
        $output = str_replace("%style", (not_empty_bool($this->_max) ? "style=\"" . $this->_style . "\"" : ""), $output);
        $output = str_replace("%options", not_empty_bool($this->_options_html) ? $this->_options_html : "", $output);
        $output = str_replace("%selected", (not_empty_bool($this->_selected) ? $this->_selected : ""), $output);
        $output = str_replace("%icon", (not_empty_bool($this->_icon) ? $this->_icon : ""), $output);
        $output = str_replace("%blur", (not_empty_bool($this->_blur) ? "onblur=\"" . $this->_blur . "\"" : ""), $output);
        if ($this->_field_type === "button") {
            if (not_empty($this->getValue())) $output = str_replace("%value", translate($this->getValue()), $output);
        } elseif ($this->_custom_type !== "password") {
            if (not_empty($this->getValue())) $output = str_replace("%value", $this->getValue(), $output);
            else $output = str_replace("%value", "", $output);
        } else {
            $output = str_replace("%value", "", $output);
        }
        $this->reset();
        return $output;
    }


    public function optionsHTML($html = null, $set_custom = false): Fields
    {
        if (!not_empty($html) && $set_custom === false) {
            $text = new Text();
            $options_html = "";
            foreach ($this->_options as $key => $value) {
                $selected = "";
                if ($text->equalsIgnoreCase($this->_value, $key)) $selected = "selected";
                $options_html .= "<option value=\"$key\" $selected>$value</option>";
            }
            $this->_options_html = $options_html;
        } else {
            $this->_options_html = $html;
        }
        return $this;
    }


    public function hashString($string): string
    {
        return md5($string);
    }

    public function hash(): Fields
    {
        $this->_name = not_empty_bool($this->_name) ? $this->hashString($this->_name) : "";
        $this->_id = not_empty_bool($this->_id) ? $this->hashString($this->_id) : "";
        return $this;
    }


    private function reset()
    {
        $this->_required = false;
        $this->_disabled = false;
        $this->_readonly = false;
        $this->_label = "";
        $this->_class = "spc-input form-control";
        $this->_labelclass = "";
        $this->_style = "";
        $this->_markdown = "";
        $this->_template = "";
        $this->_options = [];
        $this->_value = "";
        $this->_placeholder = "";
        $this->_id = "";
        $this->_name = "";
        $this->_icon = "";
        $this->_blur = "";
        $this->_selected = "";
        $this->_maxlength = "";
        $this->_min = "";
        $this->_max = "";
        $this->_custom_script = "";
        $this->_options_html = "";

    }

    private function getValue(): ?string
    {

        if (not_empty($this->_value)) return $this->_value;
        if (not_empty($this->_name)) return get_request($this->_name, false, false);
        return null;

    }

}