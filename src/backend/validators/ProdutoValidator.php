<?php

class ProdutoValidator {
    public static function validarCampos($data) {
        $erros = [];

        if (empty($data->nome)) {
            $erros[] = "O nome do produto não pode ser vazio!";
        }
        if (!isset($data->preco) || !is_numeric($data->preco) || $data->preco <= 0) {
            $erros[] = "Preço inválido! Deve ser um valor numérico positivo.";
        }
        if (empty($data->descricao)) {
            $erros[] = "A descrição do produto não pode ser vazia!";
        }
        if (!isset($data->quantidade) || !is_int($data->quantidade) || $data->quantidade < 0) {
            $erros[] = "Quantidade inválida! Deve ser um número inteiro não negativo.";
        }

        return $erros;
    }

    public static function validarNomeExistente($nome, $db) {
        $sql = "SELECT * FROM produtos WHERE nome = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "O nome do produto já existe!";
        }

        return null;
    }

    public static function validar($data, $db) {
        $erros = self::validarCampos($data);

        $nomeExistenteErro = self::validarNomeExistente($data->nome, $db);
        if ($nomeExistenteErro) {
            $erros[] = $nomeExistenteErro;
        }

        return empty($erros) ? true : $erros;
    }
}
