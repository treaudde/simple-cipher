<?php
namespace SimpleCipher;

/**
 * SimpleCipher
 *
 * Daily project - create a class that can be configured to simple encrypt and decrypt messages
 * based on a configurable code, just for fun, will expand later
 */
class SimpleCipher
{
    /**
     * Dictionary to use for encoding the message
     * letter => symbol eg ['A' => 1, 'B' => $]
     *
     * @var Array
     */
    protected $keys;

    /**
     * Delimiter for plain text words, generally spaces
     *
     * @var string
     */
    protected $wordDelimiter;

    /**
     * delimiter for the encrypted words
     *
     * @var string
     */
    protected $encryptedWordDelimiter;

    /**
     * Filters to apply to the message
     *
     * @var Array
     */
    protected $messageFilters;


    const NORMALIZE_SPACING = 'normalizeSpacing';

    const APPLY_ENCRYPTED_DELIMITER = 'applyEncryptedDelimiter';

    const APPLY_DECRYPTED_DELIMITER = 'applyDecryptedDelimiter';

    /**
     * SimpleCipher constructor.
     *
     * @param $keys
     * @param string $wordDelimiter
     * @param string $encryptedWordDelimiter
     */
    public function __construct($keys, $wordDelimiter = " ", $encryptedWordDelimiter = "-")
    {
        $this->keys = $keys;
        $this->wordDelimiter = $wordDelimiter;
        $this->encryptedWordDelimiter = $encryptedWordDelimiter;

        $this->setUpMessageFilters();
    }

    /**
     * Encrypt the message
     *
     * @param string $plainTextMessage
     *
     * @return string
     */
    public function encryptMessage(string $plainTextMessage) : string
    {
        foreach($this->keys as $letter => $symbol) {
            $plainTextMessage = preg_replace("/{$letter}/", $symbol, $plainTextMessage);
        }
        return $this->messageFilters[self::APPLY_ENCRYPTED_DELIMITER]($plainTextMessage);
    }

    /**
     * Decrypt the message
     *
     * @param string $encryptedMessage
     *
     * @return string
     */
    public function decryptMessage(string $encryptedMessage) : string
    {
        foreach($this->keys as $letter => $symbol) {
            $encryptedMessage = preg_replace("/{$symbol}/", $letter, $encryptedMessage);
        }
        return $this->messageFilters[self::APPLY_DECRYPTED_DELIMITER]($encryptedMessage);
    }

    /**
     * Set up the different filters
     */
    private function setUpMessageFilters()
    {
        $this->filters[self::NORMALIZE_SPACING] = function ($message) {
            return preg_replace("/\s{2,}/", ' ', $message);
        };

        $this->filters[self::APPLY_ENCRYPTED_DELIMITER] = function ($message) {
            return preg_replace("/\s{1}/", $this->encryptedWordDelimiter, $message);
        };

        $this->filters[self::APPLY_DECRYPTED_DELIMITER] = function ($message) {
            return preg_replace("/\-/", $this->wordDelimiter, $message);
        };
    }
}
