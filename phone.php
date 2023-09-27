<?php

    interface PhoneRepositoryInterface
    {
        public function getAllPhones(): array;
        public function getDefaultPhone(): string;
        public function getPhoneByCity(string $city): string;
    }

    class PhoneRepository implements PhoneRepositoryInterface
    {
        public function getAllPhones(): array
        {
            return [
                'moscow' => '8-800-000-00-01',
                'ekaterinburg' => '8-800-000-00-02',
                'kaliningrad' => '8-800-000-00-03',
                'vladivostok' => '8-800-000-00-04'
            ];
        }

        public function getDefaultPhone(): string
        {
            return '8-800-000-00-99';
        }

        public function getPhoneByCity(string $city): string
        {
            return $this->getAllPhones()[strtolower($city)] ?? $this->getDefaultPhone();
        }
    }

    /**
     * Если бэк сам отдаёт некую готовую HTML-страницу.
     */
    abstract class Page
    {
        protected string $path;

        public function __construct(string $path)
        {
            $this->path = $path;
        }

        protected function getPageContent(): string
        {
            $content = file_get_contents($this->path);

            if ($content === false) {
                throw new Exception('Не удалось получить содержимое страницы.');
            }

            return $content;
        }

        abstract public function render(): string;
    }

    class ContactPage extends Page
    {
        const DEFAULT_PHONE = '8-800-DIGITS';

        private string $city;
        private PhoneRepositoryInterface $phoneRepository;

        public function __construct(string $path, string $city, PhoneRepositoryInterface $phoneRepository)
        {
            $this->city = $city;
            $this->phoneRepository = $phoneRepository;
            parent::__construct($path);
        }

        private function replacePhone(string $content, string $phone): string
        {
            return str_replace(self::DEFAULT_PHONE, $phone, $content);
        }

        public function render(): string
        {
            $pageContent = $this->getPageContent();
            $phone = $this->phoneRepository->getPhoneByCity($this->city);
            
            return $this->replacePhone($pageContent, $phone);
        }
    }

    /**
     * Если фронт сайта получает данные с бэка, используя API.
     * Контролер обслуживает запрос вида: https://api.blabla.ru/info/phone/citySlug.
     */
    class PhoneController
    {
        private PhoneRepositoryInterface $phoneRepository;

        public function __construct(PhoneRepositoryInterface $phoneRepository)
        {
            $this->phoneRepository = $phoneRepository;
        }

        public function show(string $city): string
        {
            $phone = $this->phoneRepository->getPhoneByCity($city);
            $data = [
                'data' => [
                    'attributes' => [
                        'phone' => $phone
                    ]
                ]
            ];

            return json_encode($data);
        }
    }

    echo "\nPage:\n";
    $contactPage = new ContactPage('contact.html', 'vladivostok', new PhoneRepository());

    try {
        echo $contactPage->render();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    echo "\n\nAPI:\n";
    $controller = new PhoneController(new PhoneRepository());
    echo $controller->show('ekaterinburg');
?>