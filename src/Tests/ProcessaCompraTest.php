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

        $carrinhoOnzeItens =  new Carrinho($pedro);
        $carrinhoOnzeItens->adicionaProduto(new Produto('Geladeira', 1500));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Forno Eletrico', 4500));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Pia', 500));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Freezer', 2000));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Cooktop', 600));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Fogao', 1000));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Cadeiras Jantar', 500));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Air Fryer', 200));
        $produto = new Produto('Mesa Jantar', 500);
        $carrinhoOnzeItens->adicionaProduto($produto);
        $carrinhoOnzeItens->removeProduto($produto);
        $carrinhoOnzeItens->adicionaProduto($produto);
        $carrinhoOnzeItens->adicionaProduto(new Produto('Talheres', 150));
        $carrinhoOnzeItens->adicionaProduto(new Produto('Micro-ondas', 700));

        return [
            'carrinho vazio' =>  [$carrinhoVazio],
            'carrinho um item' =>  [$carrinhoUmItem],
            'carrinho onze itens' =>  [$carrinhoOnzeItens],
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

        var_dump($carrinho->getMenores());

        // Act - When
        $produtosDeMaioresValores = $carrinho->getMaiores();
        $produtosDeMenoresValores = $carrinho->getMenores();
        
        // Assert - Then
        $totalEsperadoMaiores[] = 1500.0;
        $totalEsperadoMaiores[] = 2000.0;
        $totalEsperadoMaiores[] = 4500.0;

        $totalEsperadoMenores[] = 200.0;
        $totalEsperadoMenores[] = 500.0;
        $totalEsperadoMenores[] = 600.0;
        
        self::assertEquals($totalEsperadoMenores, $produtosDeMenoresValores);
        self::assertEquals($totalEsperadoMaiores, $produtosDeMaioresValores);
        
    }
}
