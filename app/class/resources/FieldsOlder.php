<?php

class FieldsOlder
{

    public function __construct()
    {

    }

    public function render(array $fields): ?string
    {
        $output = "";
        $required = $disabled = $data_attributes = $custom_attributes = $options_inputs = $name = $field_value = "";
        $initial_class = "nx-input";
        try {
            for ($i = 0; $i < count($fields); $i++) {
                $field = $fields[$i];


                if ($this->attribute("name", $field)) {
                    $name = "name=\"" . $field['name'] . "\" id=\"" . $field['name'] . "\"";
                }


                if ($this->attribute("readonly", $field) && $field['readonly']) {
                    $field_html = '<label class="nx-input-wrap nx-input-md">';
                    $field_html .= $this->attribute("legend", $field) ? "<b class=\"nx-label-text\">*" . translate($field['legend']) . "</b>" : "";
                    $field_html .= "<div class=\"input-icon-wrap %1\%s\" %4\$s><div class=\"nx-input readonly\"><p>%7\$s</p></div></div>";
                    $field_html .= '</label>';
                } else {
                    if ($this->attribute("type", $field) && $field['type'] === "select") {
                        $initial_class = "nx-select";
                        $field_html = '<div class="nx-input-wrap nx-input-md">';
                        $field_html .= $this->attribute("name", $field) ? '<label for=\"' . $field['name'] . '\">' : '<label>';
                        $field_html .= $this->attribute("legend", $field) ? "<b class=\"nx-label-text\">*" . translate($field['legend']) . "</b>" : "";
                        $field_html .= "<select $name %1\$s %2\$s %3\$s %4\$s %5\$s>%6\$s</select>";
                        $field_html .= '</label>';
                        $field_html .= '</div>';
                    } elseif ($this->attribute("type", $field) && $field['type'] === "textarea") {
                        $field_html = '<label class="nx-input-wrap nx-input-md">';
                        $field_html .= $this->attribute("legend", $field) ? "<b class=\"nx-label-text\">*" . translate($field['legend']) . "</b>" : "";
                        $field_html .= "<textarea $name %1\$s %2\$s %3\$s %4\$s %5\$s>%7\$s</textarea>";
                        $field_html .= '</label>';
                    } else {
                        $field_html = '<label class="nx-input-wrap nx-input-md">';
                        $field_html .= $this->attribute("legend", $field) ? "<b class=\"nx-label-text\">*" . translate($field['legend']) . "</b>" : "";
                        $field_html .= "<input type=\"" . $field['type'] . "\" $name  %1\$s %2\$s %3\$s %4\$s %5\$s value=\"%7\$s\">";
                        $field_html .= '</label>';
                    }
                }


                $classes = "class=\"" . $initial_class;
                if ($this->attribute("class", $field)) {
                    foreach ($field['class'] as $key) {
                        $classes .= " " . $key;
                    }
                }
                $classes .= "\"";


                if ($this->attribute("data", $field)) {
                    foreach ($field['class'] as $key => $value) {
                        $data_attributes .= "data-$key=\"$value\" ";
                    }
                }


                if ($this->attribute("required", $field) && $field['required']) {
                    $required = "required=\"required\"";
                }


                if ($this->attribute("disabled", $field) && $field['disabled']) {
                    $disabled = "disabled=\"disabled\"";
                }


                if ($this->attribute("value", $field)) {
                    $field_value = $field['value'];
                }


                if ($this->attribute("data", $field)) {
                    foreach ($field['data'] as $key => $value) {
                        $data_attributes .= "$key=\"$value\"";
                    }
                }


                if ($this->attribute("options", $field)) {
                    foreach ($field['options'] as $key => $value) {
                        $options_inputs .= "<option value=\"$key\">$value</option>";
                    }
                }


                $field_html .= '</label>';
                $field_html = sprintf($field_html, $classes, $required, $disabled, $data_attributes, $custom_attributes, $options_inputs, $field_value);


                $output .= $field_html;
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return $output;
    }

    private function attribute(string $attribute, array $field): bool
    {
        return array_key_exists($attribute, $field);
    }


}