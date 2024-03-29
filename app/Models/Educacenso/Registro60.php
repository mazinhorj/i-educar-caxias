<?php

namespace App\Models\Educacenso;

use iEducar\Modules\Educacenso\Model\EstruturaCurricular;
use iEducar\Modules\Educacenso\Model\PaisResidencia;
use iEducar\Modules\Educacenso\Model\TipoAtendimentoTurma;
use iEducar\Modules\Educacenso\Model\TipoMediacaoDidaticoPedagogico;
use Transporte_Model_Responsavel;

class Registro60 implements RegistroEducacenso, ItemOfRegistro30
{
    public $registro;
    public $inepEscola;
    public $codigoPessoa;
    public $inepAluno;
    public $inepTurma;
    public $matriculaAluno;
    public $etapaAluno;
    public $tipoItinerarioLinguagens;
    public $tipoItinerarioMatematica;
    public $tipoItinerarioCienciasNatureza;
    public $tipoItinerarioCienciasHumanas;
    public $tipoItinerarioFormacaoTecnica;
    public $tipoItinerarioIntegrado;
    public $composicaoItinerarioLinguagens;
    public $composicaoItinerarioMatematica;
    public $composicaoItinerarioCienciasNatureza;
    public $composicaoItinerarioCienciasHumanas;
    public $composicaoItinerarioFormacaoTecnica;
    public $cursoItinerario;
    public $itinerarioConcomitante;
    public $tipoAtendimentoDesenvolvimentoFuncoesGognitivas;
    public $tipoAtendimentoDesenvolvimentoVidaAutonoma;
    public $tipoAtendimentoEnriquecimentoCurricular;
    public $tipoAtendimentoEnsinoInformaticaAcessivel;
    public $tipoAtendimentoEnsinoLibras;
    public $tipoAtendimentoEnsinoLinguaPortuguesa;
    public $tipoAtendimentoEnsinoSoroban;
    public $tipoAtendimentoEnsinoBraile;
    public $tipoAtendimentoEnsinoOrientacaoMobilidade;
    public $tipoAtendimentoEnsinoCaa;
    public $tipoAtendimentoEnsinoRecursosOpticosNaoOpticos;
    public $recebeEscolarizacaoOutroEspacao;
    public $transportePublico;
    public $poderPublicoResponsavelTransporte;
    public $veiculoTransporteBicicleta;
    public $veiculoTransporteMicroonibus;
    public $veiculoTransporteOnibus;
    public $veiculoTransporteTracaoAnimal;
    public $veiculoTransporteVanKonbi;
    public $veiculoTransporteOutro;
    public $veiculoTransporteAquaviarioCapacidade5;
    public $veiculoTransporteAquaviarioCapacidade5a15;
    public $veiculoTransporteAquaviarioCapacidade15a35;
    public $veiculoTransporteAquaviarioCapacidadeAcima35;
    public $modalidadeCurso;

    /**
     * @var string Campo usado somente na análise
     */
    public $nomeEscola;

    /**
     * @var string Campo usado somente na análise
     */
    public $nomeAluno;

    /**
     * @var string Campo usado somente na análise
     */
    public $codigoAluno;

    /**
     * @var string Campo usado somente na análise
     */
    public $tipoAtendimentoTurma;

    /**
     * @var string Campo usado somente na análise
     */
    public $codigoTurma;

    /**
     * @var string Campo usado somente na análise
     */
    public $etapaTurma;

    /**
     * @var array Campo usado somente na análise
     */
    public $estruturaCurricularTurma;

    /**
     * @var integer Campo usado somente na análise
     */
    public $enturmacaoId;

    /**
     * @var string Campo usado somente na análise
     */
    public $codigoMatricula;

    /**
     * @var string Campo usado somente na análise
     */
    public $nomeTurma;

    /**
     * @var string Campo usado somente na análise
     */
    public $tipoAtendimentoMatricula;

    /**
     * @var string Campo usado somente na análise
     */
    public $tipoMediacaoTurma;

    /**
     * @var string Campo usado somente na análise
     */
    public $veiculoTransporteEscolar;

    /**
     * @var string Campo usado somente na análise
     */
    public $localFuncionamentoDiferenciadoTurma;

    /**
     * @var string Campo usado somente na análise
     */
    public $paisResidenciaAluno;

    /**
     * @return bool
     */
    public function transportePublicoRequired()
    {
        $tiposMediacaoPresencialSemiPresencial = [
            TipoMediacaoDidaticoPedagogico::PRESENCIAL,
            TipoMediacaoDidaticoPedagogico::SEMIPRESENCIAL,
        ];

        return $this->tipoAtendimentoTurma == TipoAtendimentoTurma::ESCOLARIZACAO
            && in_array($this->tipoMediacaoTurma, $tiposMediacaoPresencialSemiPresencial)
            && $this->paisResidenciaAluno == PaisResidencia::BRASIL;
    }

    /**
     * @return bool
     */
    public function veiculoTransporteEscolarRequired()
    {
        $transportePublico = [
            Transporte_Model_Responsavel::MUNICIPAL,
            Transporte_Model_Responsavel::ESTADUAL,
        ];

        return in_array($this->transportePublico, $transportePublico);
    }

    public function isAtividadeComplementarOrAee()
    {
        return $this->tipoAtendimentoTurma == TipoAtendimentoTurma::ATIVIDADE_COMPLEMENTAR ||
            $this->tipoAtendimentoTurma == TipoAtendimentoTurma::AEE;
    }

    /**
     * @return bool
     */
    public function recebeEscolarizacaoOutroEspacoIsRequired()
    {
        return $this->tipoAtendimentoTurma == TipoAtendimentoTurma::ESCOLARIZACAO &&
            $this->tipoMediacaoTurma == TipoMediacaoDidaticoPedagogico::PRESENCIAL &&
            $this->localFuncionamentoDiferenciadoTurma == \App_Model_LocalFuncionamentoDiferenciado::NAO_ESTA &&
            $this->localFuncionamentoDiferenciadoTurma == \App_Model_LocalFuncionamentoDiferenciado::SALA_ANEXA;
    }

    /**
     * @return bool
     */
    public function analisaDadosItinerario()
    {
        if (
            in_array(EstruturaCurricular::ITINERARIO_FORMATIVO, $this->estruturaCurricularTurma) &&
            count($this->estruturaCurricularTurma) === 1
        ) {
            return true;
        }

        $etapasValidas = [25, 26, 27, 28, 30, 31, 32, 33, 35, 36, 37, 38, 71, 74];

        if (
            in_array(EstruturaCurricular::ITINERARIO_FORMATIVO, $this->estruturaCurricularTurma) &&
            in_array(EstruturaCurricular::FORMACAO_GERAL_BASICA, $this->estruturaCurricularTurma) &&
            in_array($this->etapaTurma, $etapasValidas)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function tipoItinerarioNaoPreenchido()
    {
        return
            !$this->tipoItinerarioLinguagens &&
            !$this->tipoItinerarioMatematica &&
            !$this->tipoItinerarioCienciasNatureza &&
            !$this->tipoItinerarioCienciasHumanas &&
            !$this->tipoItinerarioFormacaoTecnica &&
            !$this->tipoItinerarioIntegrado;
    }

    /**
     * @return bool
     */
    public function composicaoItinerarioNaoPreenchido()
    {
        return
            !$this->composicaoItinerarioLinguagens &&
            !$this->composicaoItinerarioMatematica &&
            !$this->composicaoItinerarioCienciasNatureza &&
            !$this->composicaoItinerarioCienciasHumanas &&
            !$this->composicaoItinerarioFormacaoTecnica;
    }

    public function etapaTurmaDescritiva()
    {
        $etapasEducacenso = loadJson('educacenso_json/etapas_ensino.json');

        return $etapasEducacenso[$this->etapaTurma];
    }

    public function getCodigoPessoa()
    {
        return $this->codigoPessoa;
    }

    public function getCodigoAluno()
    {
        return $this->codigoAluno;
    }

    public function getCodigoServidor()
    {
        return null;
    }

    /**
     * @param $column
     */
    public function hydrateModel($arrayColumns)
    {
        array_unshift($arrayColumns, null);
        unset($arrayColumns[0]);

        $this->inepEscola = $arrayColumns[2];
        $this->inepAluno = $arrayColumns[4];
        $this->inepTurma = $arrayColumns[6];
        $this->etapaAluno = $arrayColumns[8];
        $this->tipoAtendimentoDesenvolvimentoFuncoesGognitivas = $arrayColumns[9];
        $this->tipoAtendimentoDesenvolvimentoVidaAutonoma = $arrayColumns[10];
        $this->tipoAtendimentoEnriquecimentoCurricular = $arrayColumns[11];
        $this->tipoAtendimentoEnsinoInformaticaAcessivel = $arrayColumns[12];
        $this->tipoAtendimentoEnsinoLibras = $arrayColumns[13];
        $this->tipoAtendimentoEnsinoLinguaPortuguesa = $arrayColumns[14];
        $this->tipoAtendimentoEnsinoSoroban = $arrayColumns[15];
        $this->tipoAtendimentoEnsinoBraile = $arrayColumns[16];
        $this->tipoAtendimentoEnsinoOrientacaoMobilidade = $arrayColumns[17];
        $this->tipoAtendimentoEnsinoCaa = $arrayColumns[18];
        $this->tipoAtendimentoEnsinoRecursosOpticosNaoOpticos = $arrayColumns[19];
        $this->recebeEscolarizacaoOutroEspacao = $arrayColumns[20];
        $this->transportePublico = $arrayColumns[21];
        $this->poderPublicoResponsavelTransporte = $arrayColumns[22];
        $this->veiculoTransporteBicicleta = $arrayColumns[23];
        $this->veiculoTransporteMicroonibus = $arrayColumns[24];
        $this->veiculoTransporteOnibus = $arrayColumns[25];
        $this->veiculoTransporteTracaoAnimal = $arrayColumns[26];
        $this->veiculoTransporteVanKonbi = $arrayColumns[27];
        $this->veiculoTransporteOutro = $arrayColumns[29];
        $this->veiculoTransporteAquaviarioCapacidade5 = $arrayColumns[29];
        $this->veiculoTransporteAquaviarioCapacidade5a15 = $arrayColumns[30];
        $this->veiculoTransporteAquaviarioCapacidade15a35 = $arrayColumns[31];
        $this->veiculoTransporteAquaviarioCapacidadeAcima35 = (int) $arrayColumns[32];
    }
}
