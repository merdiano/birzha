<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые строки для валидации
    |--------------------------------------------------------------------------
    |
    | Следующие языковые строки содержат сообщения по умолчанию об ошибках, используемые
    | классом валидатора. Некоторые из этих правил имеют несколько версий, например
    | для правила size. Не стесняйтесь здесь настраивать каждое из этих сообщений.
    |
    */

    "accepted"             => "Вы должны принять :attribute.",
    "active_url"           => "Поле :attribute недействительный URL.",
    "after"                => "Поле :attribute должно быть датой после :date.",
    'after_or_equal'       => 'Поле :attribute должно быть датой после или равной :date.',
    "alpha"                => "Поле :attribute может содержать только буквы.",
    "alpha_dash"           => "Поле :attribute может содержать только буквы, цифры и дефис.",
    "alpha_num"            => "Поле :attribute может содержать только буквы и цифры.",
    "array"                => "Поле :attribute должно быть массивом.",
    "before"               => "Поле :attribute должно быть датой перед :date.",
    'before_or_equal'      => 'Поле :attribute должно быть датой перед или равной :date.',
    "between"              => [
        "numeric" => "Поле :attribute должно быть между :min и :max.",
        "file"    => "Размер :attribute должен быть от :min до :max Килобайт.",
        "string"  => "Длина :attribute должна быть от :min до :max символов.",
        "array"   => "Поле :attribute должно содержать :min - :max элементов.",
    ],
    'boolean'              => 'Поле :attribute должно быть true или false.',
    "confirmed"            => "Поле :attribute не совпадает с подтверждением.",
    "date"                 => "Поле :attribute не является датой.",
    'date_equals'          => 'Поле :attribute должно быть датой равной :date.',
    "date_format"          => "Поле :attribute не соответствует формату :format.",
    "different"            => "Поля :attribute и :other должны различаться.",
    "digits"               => "Длина цифрового поля :attribute должна быть :digits.",
    "digits_between"       => "Длина цифрового поля :attribute должна быть между :min и :max.",
    'dimensions'           => ':attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute имеет повторяющееся значение.',
    "email"                => "Поле :attribute имеет ошибочный формат.",
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из значений: :values.',
    "exists"               => "Выбранное значение для :attribute отсутствует.",
    'file'                 => ':attribute должен быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'numeric' => 'Поле :attribute должно быть больше чем :value.',
        'file'    => 'Файл :attribute должен быть больше :value килобайт.',
        'string'  => 'Поле :attribute должно быть больше :value символов.',
        'array'   => 'Поле :attribute должно содержать больше :value элементов.',
    ],
    'gte'                  => [
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'file'    => 'Файл :attribute должен быть больше или равен :value килобайт.',
        'string'  => 'Поле :attribute должен быть больше or equal :value символов.',
        'array'   => 'Поле :attribute должно содержать :value элементов или больше.',
    ],
    "image"                => "Поле :attribute должно быть изображением.",
    "in"                   => "Выбранное значение для :attribute ошибочно.",
    'in_array'             => 'Поле :attribute не существует в :other.',
    "integer"              => "Поле :attribute должно быть целым числом.",
    "ip"                   => "Поле :attribute должно быть действительным IP-адресом.",
    'ipv4'                 => 'Поле :attribute должно быть IPv4 адресом.',
    'ipv6'                 => 'Поле :attribute должно быть IPv6 адресом.',
    'json'                 => 'Поле :attribute должно быть JSON строкою.',
    'lt'                   => [
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'file'    => 'Файл :attribute должен быть меньше :value килобайт.',
        'string'  => 'Поле :attribute должно быть меньше :value символов.',
        'array'   => 'Поле :attribute должно содержать меньше :value элементов.',
    ],
    'lte'                  => [
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'file'    => 'Файл :attribute должен быть меньше или равен :value килобайт.',
        'string'  => 'Поле :attribute должно быть меньше или равно :value символов.',
        'array'   => 'Поле :attribute не должно содержать больше чем :value элементов.',
    ],
    "max"                  => [
        "numeric" => "Поле :attribute должно быть не больше :max.",
        "file"    => "Поле :attribute должно быть не больше :max килобайт.",
        "string"  => "Поле :attribute должно быть не длиннее :max символов.",
        "array"   => "Поле :attribute должно содержать не более :max элементов.",
    ],
    "mimes"                => "Поле :attribute должно быть файлом одного из типов: :values.",
    "mimetypes"            => "Поле :attribute должно иметь одно из расширений: :values.",
    "min"                  => [
        "numeric" => "Поле :attribute должно быть не менее :min.",
        "file"    => "Поле :attribute должно быть не менее :min килобайт.",
        "string"  => "Поле :attribute должно быть не короче :min символов.",
        "array"   => "Поле :attribute должно содержать не менее :min элементов.",
    ],
    "not_in"               => "Выбранное значение для :attribute ошибочно.",
    'not_regex'            => 'Поле :attribute имеет ошибочный формат.',
    "numeric"              => "Поле :attribute должно быть числом.",
    'present'              => 'Поле :attribute должно присутствовать.',
    "regex"                => "Поле :attribute имеет ошибочный формат.",
    "required"             => "Поле :attribute обязательно для заполнения.",
    "required_if"          => "Поле :attribute обязательно для заполнения, когда :other равно :value.",
    'required_unless'      => 'Поле :attribute обязательно если :other нет среди :values.',
    "required_with"        => "Поле :attribute обязательно для заполнения, когда :values указано.",
    'required_with_all'    => 'Поле :attribute обязательно для заполнения когда одно из :values присутствует.',
    "required_without"     => "Поле :attribute обязательно для заполнения, когда :values не указано.",
    'required_without_all' => 'Поле :attribute обязательно для заполнения когда нет ни одного из :values.',
    "same"                 => "Значение :attribute должно совпадать с :other.",
    "size"                 => [
        "numeric" => "Поле :attribute должно быть :size.",
        "file"    => "Поле :attribute должно быть :size килобайт.",
        "string"  => "Поле :attribute должно быть длиной :size символов.",
        "array"   => "Количество элементов в поле :attribute должно быть :size.",
    ],
    'starts_with'          => 'Поле :attribute должно начинаться одним из значений: :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'The :attribute must be a valid zone.',
    "unique"               => "Такое значение поля :attribute уже существует.",
    'uploaded'             => ':attribute не удалось загрузить.',
    "url"                  => "Поле :attribute имеет ошибочный формат.",
    'uuid'                 => 'Поле :attribute должно быть действительным UUID.',
    'atleast_1_image' => 'Загрузите хотя бы 1 фото.',
    'image_type' => 'Тип изображания должен быть :image_type!',
    'image_size' => 'Изображение не больше :size Mб!',
    'low_balance' => 'Пополните баланс',

    /*
    |--------------------------------------------------------------------------
    | Пользовательские языковые строки для валидации
    |--------------------------------------------------------------------------
    |
    | Здесь Вы можете указать собственные сообщения для атрибутов, используя
    | соглашение именования строк "attribute.rule". Это позволяет легко указать
    | свое сообщение для заданного правила атрибута.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Последующие строки используются для подмены программных имен элементов
    | пользовательского интерфейса на удобочитаемые. Например, вместо имени
    | поля "email" в сообщениях будет выводиться "электронный адрес".
    |
    */

    'attributes' => [
        'name_en' => 'Наименование лота EN',
        'name_ru' => 'Наименование лота RU',
        'name_tm' => 'Наименование лота TM',
        'mark' => 'Марка товара',
        'manufacturer' => 'Производитель',
        'category_id' => 'Категория',
        'country_id' => 'Страна производителя',
        'measure_id' => 'Единицы измерения',
        'quantity' => 'Количество товара',
        'currency_id' => 'Валюта',
        'price' => 'Цена за единицу',
        'delivery_term_id' => 'Условия поставки',
        'place' => 'Пункт',
        'packaging' => 'Упаковка',
        'payment_term_id' => 'Условия оплаты',
        'description_ru' => 'Описание RU',
        'description_en' => 'Описание EN',
        'description_tm' => 'Описание TM',
        'bank_file' => 'Файл из банка',
        'market_type' => 'Тип рынка',
    ],

];
