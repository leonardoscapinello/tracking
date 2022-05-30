<?php

class Numeric
{
    private $_number;
    private $_currency = "R$";

    public function set($number): Numeric
    {
        $this->_number = trim($number);
        return $this;
    }

    public function isIdentity(): bool
    {
        return (not_empty($this->_number) && $this->isNumber($this->_number) && $this->_number > 0);
    }

    public function isNumber($number)
    {
        return (preg_match('/^[0-9,.]+$/', $number));
    }

    public function double(): Numeric
    {
        if ($this->isNumber($this->_number)) $this->_number = number_format($this->_number, 2, ",", ".");
        else $this->_number = number_format(0, 2, ",", ".");
        return $this;
    }


    public function random($length = 8): Numeric
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $this->_number = "";
        for ($i = 0; $i < $length; $i++) {
            $this->_number .= $characters[rand(0, $charactersLength - 1)];
        }
        return $this;
    }


    public function subtract($number): Numeric
    {
        $this->_number = $this->_number - $number;
        return $this;
    }

    public function sum($number): Numeric
    {
        $this->_number = $this->_number + $number;
        return $this;
    }

    public function percentIncrease($number): Numeric
    {
        $this->_number = round(($number / $this->_number) * 100, 2);
        return $this;
    }


    public function hundred(): Numeric
    {
        if (!$this->isNumber($this->_number)) $this->_number = 0;
        $this->_number = round($this->_number / 100, 2);
        return $this;
    }

    public function cents(): Numeric
    {
        if (!$this->isNumber($this->_number)) $this->_number = 0;
        $this->_number = intval($this->_number) * 100;
        return $this;
    }

    public function percentDecrease($number): Numeric
    {
        $this->_number = ($this->_number / $number) * 100;
        return $this;
    }

    public function round($precision = 2): Numeric
    {
        $this->_number = round($this->_number, $precision);
        return $this;
    }

    public function multiply($times = 1): Numeric
    {
        $this->_number = ($this->_number * $times);
        return $this;
    }

    public function database($increment_cents = false): Numeric
    {
        $this->_number = (preg_replace("/[^0-9]/", "", $this->_number));
        if ($increment_cents) $this->_number = $this->_number * 100;
        $this->_number = intval($this->_number);
        return $this;
    }

    public function decode(): Numeric
    {
        $text = new Text();
        $this->_number = $text->set($this->_number)->decode()->output();
        return $this;
    }

    public function money(): Numeric
    {
        $formatter = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $this->_number = "<small class=\"currency currency-usd\">" . $this->_currency . "</small> " . $formatter->formatCurrency($this->_number, 'BRL');
        return $this;
    }

    public function integer(): Numeric
    {
        $this->_number = intval($this->_number);
        return $this;
    }

    public function validateCPF($cpf = 0): bool
    {
        if ($cpf === 0) $cpf = $this->_number;
        if (not_empty($cpf)) {
            $cpf = preg_replace('/[^0-9]/', '', (string)$cpf);
            if (strlen($cpf) !== 11) return false;
            if (preg_match('/(\d)\1{10}/', $cpf)) return false;
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function validateCNPJ($cnpj = 0): bool
    {
        if ($cnpj === 0) $cnpj = $this->_number;
        if (not_empty($cnpj)) {
            $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);
            if (strlen($cnpj) !== 14) return false;
            if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
            for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }
            $resto = $soma % 11;
            if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
                return false;
            for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }
            $resto = $soma % 11;
            return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
        }
        return false;
    }

    public function weightWithLabel(): Numeric
    {
        if ($this->isNumber($this->_number)) {
            if ($this->_number < 1000) {
                $this->_number = round($this->_number) . "g";
            } else {
                $this->_number = round(($this->_number / 1000), 1) . "kg";
            }
        }
        return $this;
    }

    public function extensive($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false): ?string
    {

        $valor = self::removeFormatExtensive($valor);
        $singular = null;
        $plural = null;
        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        if ($bolPalavraFeminina) {
            if ($valor == 1)
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            else
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;

        $valor = number_format($valor, 2, " . ", " . ");
        $inteiro = explode(" . ", $valor);

        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");

    }

    public static function removeFormatExtensive($strNumero)
    {

        $strNumero = trim(str_replace("R$", null, $strNumero));

        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1) {
            $acentos = array(" . ");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        } else if (count($vetVirgula) != 2) {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);

        $acentos = array(" . ");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado = $resultado . " . " . $strDecimal;

        return $resultado;

    }


    public function clean(): Numeric
    {
        $this->_number = (preg_replace("/[^0-9]/", "", $this->_number));
        return $this;
    }

    public function fixMobile(): Numeric
    {
        $example = "11912345678";
        if (strlen($example) === strlen($this->_number)) $this->_number = "55" . $this->_number;
        return $this;
    }

    public function zeroFill($quantity): Numeric
    {
        $this->_number = sprintf("%0" . $quantity . "d", $this->_number);
        return $this;
    }

    public function decimalDigits(): Numeric
    {
        $this->_number = sprintf('%.2f', (sprintf(" % 03d", $this->_number) / 100));
        return $this;
    }

    public function hoursMinutes($format = '%02d:%02d'): Numeric
    {
        $hours = floor($this->_number / 60);
        $minutes = ($this->_number % 60);
        $this->_number = sprintf($format, $hours, $minutes);
        return $this;
    }

    public function percentWithSymbol(): Numeric
    {
        $this->_number = round($this->_number, 2) . "%";
        return $this;
    }

    public function output()
    {
        return $this->_number;
    }


    public function compoundInterest($periods, $interest): Numeric
    {
        if ($periods >= 2) $this->_number = round($this->_number * pow(1 + ($interest / 100), $periods), 2);
        return $this;
    }

    public function simpleInterest($interest): Numeric
    {
        if ($interest > 100) $interest = $interest / 100;
        $this->_number = ($interest / 100) * $this->_number;
        return $this;
    }

    public function installmentAmount($periods): Numeric
    {
        $this->_number = round(($this->_number / $periods), 2);
        return $this;
    }

}