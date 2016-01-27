<?php

namespace Pagekit\Tools;

class Fetcher
{

    /**
     * The Transifex Api
     * @var TransifexApi
     */
    protected $api;

    /**
     * List of resources to fetch
     * @var array
     */
    protected $resources;

    /**
     * The path to store translations in
     * @var string
     */
    protected $path;

    public function __construct($username, $password, $path, $project = "pagekit-cms")
    {
        $this->api       = new TransifexApi($username, $password, $project);
        $this->path      = $path;
        $this->resources = ['system', 'blog', 'theme-one'];
    }

    public function fetch()
    {
        foreach ($this->resources as $resource) {
            $this->line("");
            $this->line("Translations for ${resource} ...");
            $this->line("");
            $this->fetchSingle($resource);
        }
    }

    /**
     * Fetches all translations for the specified extension.
     */
    protected function fetchSingle($resource)
    {
        $resource = basename($resource);

        foreach ($this->api->fetchLocales($resource) as $locale) {

            $this->line("Fetching for ${locale} ...");
            $translations = $this->api->fetchTranslations($resource, $locale);

            // New languages don't have a folder yet
            $folder = sprintf('%s/languages/%s/', $this->getPath($resource), $locale);
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            // Write translation file
            $filename = sprintf('%s/messages.php', $folder);
            $content  = sprintf('<?php return %s;', var_export($translations, true));
            file_put_contents($filename, $content);

        }
    }

    /**
     * Returns the extension path.
     *
     * @param  string $path
     * @return array
     */
    protected function getPath($resource)
    {
        return sprintf('%s/%s', $this->path, $resource);
    }

    /**
     * Print a message to the terminal.
     *
     * @param string $message Message to print
     * @return void
     */
    protected function line($message)
    {
        echo $message."\n";
    }

}
