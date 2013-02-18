<?php

class AntBuildCommonsExtensionPearPackageForceRulesetInsidePHPMD
{
  /**
     * The package manifest file.
     *
     * @var string
     */
    private $file;

    /**
     * Root directory with source files for the PEAR archive.
     *
     * @var string
     */
    private $directory;

    /**
     * Constructs a new pear package.xml updater instance.
     *
     * @param string $directory
     */
    public function __construct( $directory )
    {
        if ( is_file( $directory ) )
        {
            $this->file      = $directory;
            $this->directory = dirname( $directory );
        }
        else
        {
            $this->file      = $directory . '/package.xml';
            $this->directory = $directory;
        }
    }

    public function run()
    {
        $xml = simplexml_load_file($this->file);
        $namespaces = $xml->getDocNamespaces();
        $xml->registerXPathNamespace('__empty_ns', $namespaces['']);

        foreach($xml->xpath("/__empty_ns:package/__empty_ns:contents//__empty_ns:dir[@name='rulesets']") as $node) {
          if(!isset($node['baseinstalldir'])) {
            $node->addAttribute('baseinstalldir', '');
          }
          $node['baseinstalldir'] = '/data/PHP_PMD';
        }

        foreach($xml->xpath("/__empty_ns:package/__empty_ns:contents//__empty_ns:dir[@name='rulesets']//__empty_ns:file") as $node) {
          if(!isset($node['role'])) {
            $node->addAttribute('role', '');
          }
          $node['role'] = 'php';
        }

        $xml->saveXML($this->file);
    }

  public static function main( array $args )
    {
        $updater = new AntBuildCommonsExtensionPearPackageForceRulesetInsidePHPMD( $args[0] );
        $updater->run();
    }
}

AntBuildCommonsExtensionPearPackageForceRulesetInsidePHPMD::main( array_slice( $argv, 1 ) );