<?php

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;
use Loja\WebIII\Model\Usuario;
use Loja\WebIII\Service\ProcessaCompra;
use PHPUnit\Framework\TestCase;

class CarrinhoTest extends TestCase{
    private $compra;

    public function setUp(): void{
        $this->compra = new ProcessaCompra();
    }

    public function testVerificaSe_ARemocaoDeProdutoDoCarrinho_EstaCorreta(){
        $jose = new Usuario('Jose');
        $carrinho =  new Carrinho($jose);
        $carrinho->adicionaProduto(new Produto('Geladeira', 1500)); // 0
        $carrinho->adicionaProduto(new Produto('Forno Eletrico', 4500)); // 1
        $carrinho->adicionaProduto(new Produto('Pia', 500)); // 2
        $carrinho->adicionaProduto(new Produto('Freezer', 2000)); // 3
        $carrinho->adicionaProduto(new Produto('Freezer', 2000)); // 4
        $carrinho->adicionaProduto(new Produto('Freezer', 2000)); // 5
        $carrinho->adicionaProduto(new Produto('Freezer', 2000)); // 6
        $carrinho->adicionaProduto(new Produto('Cooktop', 600)); // 7
        $carrinho->adicionaProduto(new Produto('Fogao', 1000)); // 8
        $produto = new Produto('Cadeiras Jantar', 500);
        $carrinho->adicionaProduto($produto); // 9

        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        $carrinho->removeProduto($produto); // 8
        
        // Assert - Then
        $totalEsperado = 9;
        self::assertEquals($totalEsperado, $carrinho->getTotalDeProdutos());
    }

    public function testVerificaSe_Os3ProdutosDeMenoresValoresNoCarrinho_EstaoCorretos(){
        $jose = new Usuario('Jose');
        $carrinho =  new Carrinho($jose);
        $carrinho->adicionaProduto(new Produto('Geladeira', 1500)); // 0
        $carrinho->adicionaProduto(new Produto('Forno Eletrico', 4500)); // 1
        $carrinho->adicionaProduto(new Produto('Pia', 500)); // 2
        $carrinho->adicionaProduto(new Produto('Freezer', 2000)); // 3
        $carrinho->adicionaProduto(new Produto('Cooktop', 600)); // 4
        $carrinho->adicionaProduto(new Produto('Fogao', 1000)); // 5
        $carrinho->adicionaProduto(new Produto('Cadeiras Jantar', 500)); // 6
        $carrinho->adicionaProduto(new Produto('Air Fryer', 200)); // 7

        // Act - When
        $produtosDeMaioresValores = $carrinho->getMaiores();
        $produtosDeMenoresValores = $carrinho->getMenores();
        
        // Assert - Then
        $totalEsperadoMaiores[] = 1500.0;
        $totalEsperadoMaiores[] = 2000.0;
        $totalEsperadoMaiores[] = 4500.0;

        $totalEsperadoMenores[] = 200.0;
        $totalEsperadoMenores[] = 500.0;
        $totalEsperadoMenores[] = 500.0;
        
        self::assertEquals($totalEsperadoMenores, $produtosDeMenoresValores);
        self::assertEquals($totalEsperadoMaiores, $produtosDeMaioresValores);
    }
}
