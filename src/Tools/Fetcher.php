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

    /**
     * The minimum coverage of translation strings to be included.
     * A value of i.e. 0.6 mean that a langauge is only included if at
     * least 60% of original strings are translated in this language.
     */
    protected $coverage;

    public function __construct($username, $password, $path, $coverage = 0.6, $project = "pagekit-cms")
    {
        $this->api       = new TransifexApi($username, $password, $project);
        $this->path      = $path;
        $this->resources = ['system', 'blog', 'theme-one'];
        $this->coverage  = $coverage;
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

        list($locales, $count) = $this->api->fetchLocales($resource);

        foreach ($locales as $locale) {

            $this->line("Fetching for ${locale} ...");
            $translations = $this->api->fetchTranslations($resource, $locale);

            if(count(array_filter($translations)) < $count * $this->coverage) {
                $this->line("Skipping ${locale} because translation coverage is too low.");
                continue;
            }

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
