<?php

use Loja\WebIII\Model\Carrinho;
use Loja\WebIII\Model\Produto;
use Loja\WebIII\Model\Usuario;
use Loja\WebIII\Service\ProcessaCompra;
use PHPUnit\Framework\TestCase;

class ProcessaCompraTest extends TestCase{
    private $compra;

    public function setUp(): void{
        $this->compra = new ProcessaCompra();
    }

    public static function carrinhoComProdutos(){
        // Arrange - Given
        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $pedro = new Usuario('Pedro');
       
        $carrinhoVazio =  new Carrinho($maria);

        $carrinhoUmItem =  new Carrinho($joao);
        $carrinhoUmItem->adicionaProduto(new Produto('Forno Eletrico', 4500));

        $carrinhoDezItens =  new Carrinho($pedro);
        $carrinhoDezItens->adicionaProduto(new Produto('Geladeira', 1500));
        $carrinhoDezItens->adicionaProduto(new Produto('Forno Eletrico', 4500));
        $carrinhoDezItens->adicionaProduto(new Produto('Pia', 500));
        $carrinhoDezItens->adicionaProduto(new Produto('Freezer', 2000));
        $carrinhoDezItens->adicionaProduto(new Produto('Cooktop', 600));
        $carrinhoDezItens->adicionaProduto(new Produto('Fogao', 1000));
        $carrinhoDezItens->adicionaProduto(new Produto('Cadeiras Jantar', 500));
        $carrinhoDezItens->adicionaProduto(new Produto('Air Fryer', 200));
        $carrinhoDezItens->adicionaProduto(new Produto('Talheres', 150));
        $carrinhoDezItens->adicionaProduto(new Produto('Micro-ondas', 700));

        return [
            'carrinho vazio' =>  [$carrinhoVazio],
            'carrinho um item' =>  [$carrinhoUmItem],
            'carrinho dez itens' =>  [$carrinhoDezItens],
        ];
    }

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function testVerificaSe_OValorTotalDaCompraEASomaDosProdutosDoCarrinho_SaoIguais(Carrinho $carrinho){
        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        $totalDaCompra = $this->compra->getTotalDaCompra();
        
        // Assert - Then
        $totalEsperado = $carrinho->getValorTotalProdutos();
        
        self::assertEquals($totalEsperado, $totalDaCompra);
    }

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function testVerificaSe_AQuantidadeDeProdutosEmCompraECarrinho_SaoIguais(Carrinho $carrinho){
        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        $totalDeProdutosDaCompra = $this->compra->getTotalDeProdutos();
        
        // Assert - Then
        $totalEsperado = $carrinho->getTotalDeProdutos();
        
        self::assertEquals($totalEsperado, $totalDeProdutosDaCompra);
    }

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function testVerificaSe_OProdutoDeMaiorValorNoCarrinho_EstaCorreto(Carrinho $carrinho){
        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        $produtoDeMaiorValor = $this->compra->getProdutoDeMaiorValor();
        
        // Assert - Then
        $totalEsperado = $carrinho->getMaiorValorProduto();
        
        self::assertEquals($totalEsperado, $produtoDeMaiorValor);
    }

    /**
    * @dataProvider carrinhoComProdutos
    */
    public function testVerificaSe_OProdutoDeMenorValorNoCarrinho_EstaCorreto(Carrinho $carrinho){
        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        $produtoDeMenorValor = $this->compra->getProdutoDeMenorValor();
        
        // Assert - Then
        $totalEsperado = $carrinho->getMenorValorProduto();
        
        self::assertEquals($totalEsperado, $produtoDeMenorValor);
    }

    public function testVerificaSe_AExcessaoDeQuantidadeEValorDeProdutos_FoiLancada(){
        // Arrange - Given
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
        $carrinho->adicionaProduto(new Produto('Cadeiras Jantar', 500)); // 9
        // Act - Then
        $this->expectException(Exception::class);
        $carrinho->adicionaProduto(new Produto('Air Fryer', 200)); // 10
        // Act - When
        $this->compra->finalizaCompra($carrinho);
        
        
    }

}
