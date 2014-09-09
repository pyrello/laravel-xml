<?php namespace Pyrello\LaravelXml;

use Illuminate\Http\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class XmlResponse extends Response
{
    use ResponseTrait;

    /**
     * The response data.
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int    $status
     * @param  array  $headers
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $this->setData($data);
    }

    /**
     * {@inheritDoc}
     */
    public static function create($data = null, $status = 200, $headers = array())
    {
        return new static($data, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data = array())
    {
        $this->data = XmlTools::serialize($data);
        return $this->update();
    }

    /**
     * Updates the content and headers according to the XML data and callback.
     *
     * @return XmlResponse
     */
    protected function update()
    {
        $this->headers->set('Content-Type', 'text/xml');
        return $this->setContent($this->data);
    }
}
