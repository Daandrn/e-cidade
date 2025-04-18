<?php

    /**
     * Class Directories
     */
    class Directories {
        /**
         * @var string O caminho base onde os componentes est�o localizados.
         */
        private $baseUrl;

        /**
         * @var string O caminho completo do arquivo atual.
         */
        private $filePath;

        /**
         * @var string O diret�rio onde o arquivo atual est� localizado.
         */
        private $folderPath;

        /**
         * @var string O diret�rio atual de trabalho.
         */
        private $projectPath;

        /**
         * @var string O host HTTP da solicita��o.
         */
        private $httpHost;

        /**
         * @var string O esquema de solicita��o (http ou https).
         */
        private $requestScheme;

        /**
         * @var string A uri de solicita��o.
         */
        private $requestUri;

        /**
         * @var string O diret�rio raiz do documento.
         */
        private $documentRoot;

        /**
         * ComponentRenderer constructor.
         *
         * @param string $baseUrl O caminho base onde os componentes est�o localizados. Default � './components/'.
         */
        public function __construct($api = false)
        {
            // Caminho completo do arquivo atual
            $this->filePath = __FILE__; // EX: /var/www/{projectName}/libs/renderComponents/src/helpers/ComponentRenderer.php

            // Diret�rio do arquivo atual
            $this->folderPath = __DIR__; // EX: /var/www/{projectName}/libs/renderComponents/src/helpers

            // Diret�rio atual de trabalho
            $this->projectPath = getcwd(); // EX: /var/www/{projectName}
            
            // Definindo HTTP_HOST e REQUEST_SCHEME REQUEST_URI a partir das vari�veis globais do servidor
            $this->httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost'; // EX: [HTTP_HOST] => 10.250.30.8
            $this->requestScheme = $_SERVER['REQUEST_SCHEME'] ?? 'http'; // EX: [REQUEST_SCHEME] => http
            $this->requestUri = $_SERVER['REQUEST_URI'] ?? '1'; // EX: [REQUEST_URI] => /{project}}/w/1/{file}.php
        
            // Diret�rio raiz do documento
            $this->documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? ''; // EX: [DOCUMENT_ROOT] => /var/www

            if ($api) {
                // Define a URL base para os componentes
                $this->baseUrl = $this->requestScheme . "://" . $this->httpHost . "/" . $this->relativePath() . "/w/0";
            } else {
                // Define a URL base para os componentes
                $this->baseUrl = $this->requestScheme . "://" . $this->httpHost . "/" . $this->relativePath() . "/w/" . $this->uriNumberWindow();
            }
        }

        /**
         * Extrai e retorna um identificador num�rico da URI da solicita��o.
         *
         * Esta fun��o utiliza uma express�o regular para corresponder e extrair um valor num�rico
         * seguindo o padr�o 'w/{n�mero}' dentro da URI da solicita��o. Este identificador � 
         * comumente usado para referenciar janelas ou se��es espec�ficas na aplica��o.
         *
         * @return int O identificador num�rico extra�do da URI da solicita��o.
         */
        private function uriNumberWindow()
        {
            preg_match('/w\/(\d+)/', $this->requestUri, $matches);

            if(empty($matches[1])) {
                $numero = 0;
            } else {
                $numero = $matches[1];
            }

            return $numero;
        }

        /**
         * Calcula e retorna o caminho relativo do projeto em rela��o ao DOCUMENT_ROOT.
         *
         * Esta fun��o calcula o caminho relativo do diret�rio do projeto em rela��o
         * ao DOCUMENT_ROOT do servidor. Ela remove a por��o absoluta do caminho que 
         * corresponde ao DOCUMENT_ROOT, garantindo que o resultado seja um caminho 
         * relativo limpo, come�ando a partir do diret�rio raiz do projeto. Qualquer 
         * barra inicial � removida para manter a consist�ncia.
         *
         * @return string O caminho relativo calculado do projeto.
         */
        private function relativePath()
        {
            // Calcula o caminho relativo do projeto em rela��o ao DOCUMENT_ROOT
            $relativePath = str_replace($this->documentRoot, '', $this->projectPath);
            $relativePath = ltrim($relativePath, '/'); // Remove barra inicial, se existir
            return $relativePath;
        }

        /**
         * Obt�m o valor da URL base configurada.
         *
         * Esta fun��o � respons�vel por retornar a URL base armazenada na propriedade
         * `$baseUrl` da classe. A URL base pode ser usada em diferentes contextos
         * dentro da aplica��o, como para construir URLs absolutas ou fazer requisi��es
         * para o servidor de uma API.
         *
         * Exemplo de uso:
         * $urlBase = $objeto->getBaseUrl();
         * 
         * @return string A URL base configurada na classe.
         */
        public function getBaseUrl() {
            return $this->baseUrl;
        }
    }
?>
