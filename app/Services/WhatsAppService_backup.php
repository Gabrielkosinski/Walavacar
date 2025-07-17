<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Templates de mensagens do WhatsApp
     */
    private static function getTemplates()
    {
        return [
            'carro_pronto' => [
                'emoji' => '✅',
                'titulo' => 'Veículo Pronto!',
                'template' => "🚗 *Auto Premier Detalhes Automotivos WA* — Veículo Pronto para Retirada!\n\n" .
                            "Olá {cliente}, tudo certo?\n\n" .
                            "Seu carro já está pronto para ser retirado:\n\n" .
                            "▶️ {marca} {modelo}\n" .
                            "▶️ Placa: {placa}\n" .
                            "▶️ Serviço: {servico}\n" .
                            "▶️ Valor: R$ {valor}\n\n" .
                            "📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210\n" .
                            "🕐 Atendimento: Segunda a Sábado, das 8h às 18h\n\n" .
                            "Aguardamos você!\n" .
                            "Obrigado por escolher a Auto Premier."
            ],
            
            'cliente_chamado' => [
                'emoji' => '🎯',
                'titulo' => 'Sua vez chegou!',
                'template' => "🚗 *Auto Premier Detalhes Automotivos WA* — É a sua vez!\n\n" .
                            "Olá {cliente}, tudo certo?\n\n" .
                            "Chegou a sua vez! Pode trazer seu veículo:\n\n" .
                            "▶️ {marca} {modelo}\n" .
                            "▶️ Placa: {placa}\n" .
                            "▶️ Serviço: {servico}\n" .
                            "▶️ Valor: R$ {valor}\n" .
                            "▶️ Tempo estimado: {tempo_estimado}\n\n" .
                            "📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210\n" .
                            "🕐 Atendimento: Segunda a Sábado, das 8h às 18h\n\n" .
                            "Estamos te esperando!\n" .
                            "Obrigado por escolher a Auto Premier."
            ],
            
            'agendamento_confirmado' => [
                'emoji' => '📅',
                'titulo' => 'Agendamento Confirmado',
                'template' => "🚗 *Auto Premier Detalhes Automotivos WA* — Agendamento Confirmado!\n\n" .
                            "Olá {cliente}, tudo certo?\n\n" .
                            "Seu agendamento foi confirmado com sucesso:\n\n" .
                            "▶️ {marca} {modelo}\n" .
                            "▶️ Placa: {placa}\n" .
                            "▶️ Serviço: {servico}\n" .
                            "▶️ Data: {data}\n" .
                            "▶️ Horário: {horario}\n" .
                            "▶️ Valor: R$ {valor}\n\n" .
                            "📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210\n" .
                            "🕐 Atendimento: Segunda a Sábado, das 8h às 18h\n\n" .
                            "Nos vemos em breve!\n" .
                            "Obrigado por escolher a Auto Premier."
            ],
            
            'lembrete_agendamento' => [
                'emoji' => '⏰',
                'titulo' => 'Lembrete de Agendamento',
                'template' => "🚗 *Auto Premier Detalhes Automotivos WA* — Lembrete!\n\n" .
                            "Olá {cliente}, tudo certo?\n\n" .
                            "Lembrete do seu agendamento para *hoje*:\n\n" .
                            "▶️ {marca} {modelo}\n" .
                            "▶️ Placa: {placa}\n" .
                            "▶️ Serviço: {servico}\n" .
                            "▶️ Horário: {horario}\n" .
                            "▶️ Valor: R$ {valor}\n\n" .
                            "📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210\n" .
                            "🕐 Atendimento: Segunda a Sábado, das 8h às 18h\n\n" .
                            "Te esperamos!\n" .
                            "Obrigado por escolher a Auto Premier."
            ],
            
            'promocao' => [
                'emoji' => '🎉',
                'titulo' => 'Promoção Especial',
                'template' => "🚗 *Auto Premier Detalhes Automotivos WA* — Promoção Especial!\n\n" .
                            "Olá {cliente}, tudo certo?\n\n" .
                            "Temos uma promoção especial para você:\n\n" .
                            "▶️ {promocao_titulo}\n" .
                            "▶️ {promocao_descricao}\n" .
                            "▶️ Economia: R$ {economia}\n" .
                            "▶️ Válido até: {validade}\n\n" .
                            "📍 Endereço: R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210\n" .
                            "🕐 Atendimento: Segunda a Sábado, das 8h às 18h\n\n" .
                            "Não perca esta oportunidade!\n" .
                            "Obrigado por escolher a Auto Premier."
            ]
        ];
    }

    /**
     * Gerar URL do WhatsApp com mensagem personalizada
     */
    public static function gerarUrlWhatsApp($telefone, $tipoMensagem, $dados = [])
    {
        $templates = self::getTemplates();
        
        if (!isset($templates[$tipoMensagem])) {
            throw new \Exception("Template de mensagem '{$tipoMensagem}' não encontrado");
        }
        
        $template = $templates[$tipoMensagem]['template'];
        $mensagem = self::substituirVariaveis($template, $dados);
        
        // Limpar e formatar o telefone
        $telefoneFormatado = self::formatarTelefone($telefone);
        
        return "https://wa.me/55{$telefoneFormatado}?text=" . urlencode($mensagem);
    }

    /**
     * Substituir variáveis no template
     */
    private static function substituirVariaveis($template, $dados)
    {
        foreach ($dados as $chave => $valor) {
            $template = str_replace("{{$chave}}", $valor, $template);
        }
        
        return $template;
    }

    /**
     * Formatar telefone para WhatsApp
     */
    private static function formatarTelefone($telefone)
    {
        // Remove tudo que não é número
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        // Se tem 11 dígitos, retorna como está
        if (strlen($telefone) == 11) {
            return $telefone;
        }
        
        // Se tem 10 dígitos, adiciona o 9
        if (strlen($telefone) == 10) {
            return substr($telefone, 0, 2) . '9' . substr($telefone, 2);
        }
        
        return $telefone;
    }

    /**
     * Obter dados padrão da empresa
     */
    public static function getDadosEmpresa()
    {
        return [
            'nome_empresa' => 'Auto Premier Detalhes Automotivos WA',
            'filial' => 'Matriz',
            'telefone_contato' => '(41) 99687-5650',
            'whatsapp_business' => '5541996875650',
            'horario_funcionamento' => 'Segunda a Sábado, das 8h às 18h',
            'endereco' => 'R. Leila Diniz, 162 - Maria Antonieta, Pinhais - PR, 83331-210'
        ];
    }

    /**
     * Obter templates disponíveis para exibição
     */
    public static function getTemplatesParaExibicao()
    {
        return self::getTemplates();
    }

    /**
     * Gerar mensagem formatada com dados de exemplo
     */
    public static function gerarExemploMensagem($tipoMensagem)
    {
        $templates = self::getTemplates();
        
        if (!isset($templates[$tipoMensagem])) {
            return "Template não encontrado";
        }
        
        $dadosExemplo = [
            'cliente' => 'João Silva',
            'marca' => 'Honda',
            'modelo' => 'Civic',
            'placa' => 'ABC-1234',
            'servico' => 'Lavagem Completa + Enceramento',
            'valor' => '45,00',
            'tempo_estimado' => '30 minutos',
            'data' => date('d/m/Y'),
            'horario' => '14:30',
            'promocao_titulo' => 'Lavagem + Enceramento',
            'promocao_descricao' => 'Combo completo para seu veículo',
            'economia' => '15,00',
            'validade' => date('d/m/Y', strtotime('+7 days'))
        ];
        
        return self::substituirVariaveis($templates[$tipoMensagem]['template'], $dadosExemplo);
    }
}
