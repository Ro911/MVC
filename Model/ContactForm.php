<?php


class ContactForm
{
    public $name;
    public $email;
    public $message;

    /**
     * ContactForm constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->name = $request->post('name');
        $this->email = $request->post('email');
        $this->message = $request->post('message');
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        // TODO
        return true;
    }


}