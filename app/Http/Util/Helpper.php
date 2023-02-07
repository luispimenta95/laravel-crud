<?php

namespace App\Http\Util;


class Helpper
{
    public function recuperarMensagensPadrao() : array{
        $telefone = "61998690313";
        $mensagens = array(
            "cadastro" => "<div class='alert alert-success'> Registro cadastrado com sucesso ! </div>",
            "edicao" => "<div class='alert alert-success'> Registro atualizado com sucesso! </div>",
            "semRegistro" => "<div class='alert alert-warning'> O sistema não encontrou nenhum resultado,por favor tente novamente. </div>",
            "loginIncorreto" => "<div class='alert alert-danger'> Login ou senha incorretos , por favor tente novamente. </div>",
            "loginVazio" => "<div class='alert alert-danger'> Login ou senha não informado , por favor tente novamente. </div>",
            "logout" => "<div class='alert alert-success'> Logout realizado com sucesso. </div>",
            "erroCadastro" => "<div class='alert alert-danger'> Erro ao cadastrar o registro , favor entre em contato com o telefone $telefone para solicitar uma medida corretiva. </div>",
            "erroEdicao" => "<div class='alert alert-danger'> Erro ao atualizar o registro , favor entre em contato com o telefone $telefone para solicitar uma medida corretiva. </div>",
            "exclusao" => "<div class='alert alert-success'> Registro excluído com sucesso ! </div>",
            "erroExclusao" => "<div class='alert alert-danger'> Erro ao excluir o registro , favor entre em contato com o telefone $telefone para solicitar uma medida corretiva. </div>",
            "cpfInvalido" => "<div class='alert alert-danger'>O CPF não é válido,ou já consta em nosso sistema,favor informar outro CPF . </div>",
            "envioPedido" => "<div class='alert alert-success'> Produto adicionado ao carrinho com sucesso ! </div>",
            "erroPedido" => "<div class='alert alert-danger'> Erro ao adicionar o produto ao carrinho , por favor tente novamente. </div>",
            "atualizarPedido" => "<div class='alert alert-success'> Pedido alterado com sucesso ! </div>",
            "erroAtualizarPedido" => "<div class='alert alert-danger'> Erro ao atualizar seu pedido , por favor tente novamente. </div>",
            "cadastroHost" => "<div class='alert alert-success'> Cadastro realizado com sucesso, sua senha inicial será enviada por E-mail e deve ser alterada no primeiro acesso ! </div>",
            "erroHost" => "<div class='alert alert-danger'> Erro ao realizar seu cadastro , por favor tente novamente. </div>",
            "removerPedido" => "<div class='alert alert-success'> Produto excluido do carrinho com sucesso ! </div>",
            "erroRemoverPedido" => "<div class='alert alert-danger'> Erro ao remover produto do carrinho , por favor tente novamente ! </div>",
            "finalizarPedido" => "<div class='alert alert-success'> Pedido finalizado com sucesso ! </div>",
            "erroFinalizarPedido" => "<div class='alert alert-danger'> Erro ao finalizar seu pedido, por favor tente novamente ! </div>",
            "cpfNaoEncontrado" => "<div class='alert alert-danger'> CPF não encontrado, por favor tente novamente ! </div>",
            "erroSenhaProvisoria" => "<div class='alert alert-danger'> O campo senha provisória é divergente da senha cadastrada no sistema, por favor tente novamente ! </div>",
            "erroConfirmacao" => "<div class='alert alert-danger'> Os campos senha e nova senha são divergentes, por favor tente novamente ! </div>",
            "erroAtualizarSenhaHost" => "<div class='alert alert-danger'> Erro ao atualizar a sua senha, por favor tente novamente ! </div>",
            "atualizarSenhaHost" => "<div class='alert alert-success'> Senha alterada com sucesso, seu usuário está liberado para acessar o sistema ! </div>",
            "erroEmailDivergergente" => "<div class='alert alert-danger'> O campo email é divergente do email cadastrado no sistema, por favor tente novamente ! </div>",
            "atualizarEsqueceuSenha" => "<div class='alert alert-success'> Dados atualizados com sucesso, para acessar o sistema , por favor realize o login ! </div>",
            "definirCapa" => "<div class='alert alert-success'>Capa definida com sucesso. </div>",
            "cadastroImagem" => "<div class='alert alert-success'> Imagem (ns) cadastrada(s) com sucesso. </div>"
        );

        return $mensagens;

    }

    protected function verifica_cpf_cnpj ($valor) {
		// Verifica CPF
		if ( strlen( $valor ) === 11 ) {
			return 'CPF';
		}
		// Verifica CNPJ
		elseif ( strlen( $valor ) === 14 ) {
			return 'CNPJ';
		}
		// Não retorna nada
		else {
			return false;
		}
	}

	/**
	 * Multiplica dígitos vezes posições
	 *
	 * @access protected
	 * @param  string    $digitos      Os digitos desejados
	 * @param  int       $posicoes     A posição que vai iniciar a regressão
	 * @param  int       $soma_digitos A soma das multiplicações entre posições e dígitos
	 * @return int                     Os dígitos enviados concatenados com o último dígito
	 */
	protected function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
		// Faz a soma dos dígitos com a posição
		// Ex. para 10 posições:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
			// Preenche a soma com o dígito vezes a posição
			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

			// Subtrai 1 da posição
			$posicoes--;

			// Parte específica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( $posicoes < 2 ) {
				// Retorno a posição para 9
				$posicoes = 9;
			}
		}

		// Captura o resto da divisão entre $soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		$soma_digitos = $soma_digitos % 11;

		// Verifica se $soma_digitos é menor que 2
		if ( $soma_digitos < 2 ) {
			// $soma_digitos agora será zero
			$soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado é 11 menos $soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso dígito procurado é 2
			$soma_digitos = 11 - $soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		// Ex.: 025462884 + 2 = 0254628842
		$cpf = $digitos . $soma_digitos;

		// Retorna
		return $cpf;
	}

	/**
	 * Valida CPF
	 *
	 * @author                Luiz Otávio Miranda <contato@tutsup.com>
	 * @access protected
	 * @param  string    $cpf O CPF com ou sem pontos e traço
	 * @return bool           True para CPF correto - False para CPF incorreto
	 */
	protected function valida_cpf($valor) {
      


		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		$digitos = substr($valor, 0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		$novo_cpf = $this->calc_digitos_posicoes( $digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		$novo_cpf = $this->calc_digitos_posicoes( $novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( $novo_cpf === $valor ) {
			// CPF válido

			return true;
		} else {
			// CPF inválido
			return false;
		}
	}

	/**
	 * Valida CNPJ
	 *
	 * @author                  Luiz Otávio Miranda <contato@tutsup.com>
	 * @access protected
	 * @param  string     $cnpj
	 * @return bool             true para CNPJ correto
	 */
	protected function valida_cnpj ($valor) {
		// O valor original
		$cnpj_original = $valor;

		// Captura os primeiros 12 números do CNPJ
		$primeiros_numeros_cnpj = substr( $valor, 0, 12 );

		// Faz o primeiro cálculo
		$primeiro_calculo = $this->calc_digitos_posicoes( $primeiros_numeros_cnpj, 5 );

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		$segundo_calculo = $this->calc_digitos_posicoes( $primeiro_calculo, 6 );

		// Concatena o segundo dígito ao CNPJ
		$cnpj = $segundo_calculo;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ( $cnpj === $cnpj_original ) {
			return true;
		}
	}

	/**
	 * Valida
	 *
	 * Valida o CPF ou CNPJ
	 *
	 * @access public
	 * @return bool      True para válido, false para inválido
	 */
	public function valida ($valor) {
		// Valida CPF
		if ( $this->verifica_cpf_cnpj($valor) === 'CPF' ) {
			// Retorna true para cpf válido
			return $this->valida_cpf($valor) && $this->verifica_sequencia(11,$valor);
		}
		// Valida CNPJ
		elseif ( $this->verifica_cpf_cnpj($valor) === 'CNPJ' ) {
			// Retorna true para CNPJ válido
			return $this->valida_cnpj($valor) && $this->verifica_sequencia(14,$valor);
		}
		// Não retorna nada
		else {
			return false;
		}
	}

	/**
	 * Formata CPF ou CNPJ
	 *
	 * @access public
	 * @return string  CPF ou CNPJ formatado
	 */
	public function formata($valor) {
		// O valor formatado
		$formatado = false;

		// Valida CPF
		if ( $this->verifica_cpf_cnpj($valor) === 'CPF' ) {
			// Verifica se o CPF é válido
			if ( $this->valida_cpf($valor) ) {
				// Formata o CPF ###.###.###-##
				$formatado  = substr( $valor, 0, 3 ) . '.';
				$formatado .= substr( $valor, 3, 3 ) . '.';
				$formatado .= substr( $valor, 6, 3 ) . '-';
				$formatado .= substr( $valor, 9, 2 ) . '';

			}
		}
		// Valida CNPJ
		elseif ( $this->verifica_cpf_cnpj($valor) === 'CNPJ' ) {
			// Verifica se o CPF é válido
			if ( $this->valida_cnpj($valor) ) {
				// Formata o CNPJ ##.###.###/####-##
				$formatado  = substr( $valor,  0,  2 ) . '.';
				$formatado .= substr( $valor,  2,  3 ) . '.';
				$formatado .= substr( $valor,  5,  3 ) . '/';
				$formatado .= substr( $valor,  8,  4 ) . '-';
				$formatado .= substr( $valor, 12, 14 ) . '';
			}
		}

		// Retorna o valor
		return $formatado;
	}

	/**
	 * Método para verifica sequencia de números
	 * @param  integer $multiplos Quantos números devem ser verificados
	 * @return boolean
	 */
	public function verifica_sequencia($multiplos,$valor)
	{
		// cpf
		for($i=0; $i<10; $i++) {
			if (str_repeat($i, $multiplos) == $valor) {

				return false;
			}
		}

		return true;
	}
}