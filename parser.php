<?

class Parser
{
    private $doc = null;
    
    public function __construct($uri)
    {
        $this->doc = new DOMDocument();
        $this->doc->validateOnParse = true;
        
        try {
            if (!$this->doc->loadHTMLFile($uri)) {
                throw new Exception('DOM-object was not created');
            }
        } catch (Exception $e) {
            Logger::log($e->getMessage() . '(' . $e->getCode() . ')');
            header($_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
        }
    }

    public function getDoc()
    {
        return $this->doc;
    }
    
    
}