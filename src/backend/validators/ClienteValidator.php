<?php

class ClienteValidator {
    public static function validarCampos($data){
          $erros = [];
          
          if(empty($data->nome)){
            $erros = "o nome nao pode ser vazio!"; 
          }
          if(empty($data->email)){
            $erros = "Email nao pode ser vazio!";
          }
          if(empty($data->telefone)){
            $erros = "O telefone nao pode ser vazio!";
          }
          if(empty($data->endereco)){
            $erros = "O endereco nao pode ser vazio!";
          }

          return $erros;
    }
    // Valida o formato do email
    public static function validarEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "Email invalido!";
        }
        return null; 
    }
    // Valida formato do telefone 
    public static function validarTelefone($telefone){
        if(!preg_match("/^\(\d{2}\) \d{4,5}-\d{4}$/", $telefone)){
            return "Telefone invalido! Formato Valido: (XX) XXXXX-XXXX";
        }
        return null; 
    }

    // Valida se o email ja existe no banco de dados!
    public static function verificarEmailExistente($email, $db){
        $sql = "SELECT * FROM cliente WHERE email = ?"; 
        $stmt = $db->prepare($sql);
        $stmt->bindParam("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            return "O email fornecido ja foi registrado!"; 
        }

        return null;
    }

    // Function que valida todos os dados 
    // Retorna True caso nao existam erros
    // Caso contrario retorna um array de erros ao Controller
    public static function validar($data, $db){
        $erros = []; 

        $erros = array_merge($erros, self::validarCampos($data));

        $emailErro = self::validarEmail($data->email); 
        if($emailErro){
            $erros[] = $emailErro;
        }

        $telefoneErro = self::validarTelefone($data->telefone);
        if($telefoneErro){
            $erros[] = $telefoneErro;
        }

        $emailExistenteErro = self::verificarEmailExistente($data->email, $db);
        if ($emailExistenteErro) {
            $erros[] = $emailExistenteErro;
        }

        return empty($erros) ? true : $erros;
    }
}