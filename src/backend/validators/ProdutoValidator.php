<?php

class ProdutoValidator {
    public static function validarCampos($data){
        $erros = [];
          
        if(empty($data->nome)){
          $erros = "o nome nao pode ser vazio!"; 
        }
        if(empty($data->categoria)){
          $erros = "Informe a categoria";
        }
        if(empty($data->marca)){
          $erros = "Informe a marca";
        }
        if(empty($data->codigo_barra)){
          $erros = "Informe o codigo de barras!";
        }
        if(empty($data->preco_custo)){
            $erros = "Informe o preco de custo";
        }
        if(empty($data->preco_venda)){
            $erros = "Informe o preco de venda";
        }

        return $erros;

    }

    public static function validarCodigoBarra($codigo_barra){
        if(!preg_match("/^\d{13}$/", $codigo_barra)){
            return "Codgio de barra invalido! Deve ter exatamente 13 digitos!";
        }
        return null; 
    }

    public static function validarPrecoCusto($preco_custo){
        if(!is_numeric($preco_custo) || $preco_custo <= 0){
            return "Preco invalido!";
        }
        return null; 
    }    
    public static function validarPrecoVenda($preco_venda){
        if(!is_numeric($preco_venda) || $preco_venda <= 0){
            return "Preco invalido!";
        }
        return null; 
    }

    // Function que valida todos os dados 
    // Retorna True caso nao existam erros
    // Caso contrario retorna um array de erros ao Controller
    public static function validar($data, $db){
        $erros = []; 

        $erros = array_merge($erros, self::validarCampos($data));

        $precoCustoErro = self::validarPrecoCusto($data->preco_custo); 
        if($precoCustoErro){
            $erros[] = $precoCustoErro;
        }
        $precoVendaErro = self::validarPrecoVenda($data->preco_venda); 
        if($precoVendaErro){
            $erros[] = $precoVendaErro;
        }

        $codigoBarraErro = self::validarCodigoBarra($data->codigo_barra);
        if($codigoBarraErro){
            $erros[] = $codigoBarraErro;
        }
        
        return empty($erros) ? true : $erros;
    }
}