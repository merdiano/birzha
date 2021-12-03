<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => ':attribute kabul edilmelidir.',
    'active_url'      => ':attribute dogry URL bolmalydyr.',
    'after'           => ':attribute şundan has köne sene bolmalydyr :date.',
    'after_or_equal'  => 'The :attribute must be a date after or equal to :date.',
    'alpha'           => ':attribute dine harplardan durmalydyr.',
    'alpha_dash'      => ':attribute dine harplardan, sanlardan we tirelerden durmalydyr.',
    'alpha_num'       => ':attribute dine harplardan we sanlardan durmalydyr.',
    'array'           => ':attribute ýygyndy bolmalydyr.',
    'before'          => ':attribute şundan has irki sene bolmalydyr :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between'         => [
        'numeric' => ':attribute :min - :max arasynda bolmalydyr.',
        'file'    => ':attribute :min - :max kilobaýt arasynda bolmalydyr.',
        'string'  => ':attribute :min - :max harplar arasynda bolmalydyr.',
        'array'   => ':attribute :min - :max arasynda madda eýe bolmalydyr.',
    ],
    'boolean'        => ':attribute diňe dogry ýada ýalňyş bolmalydyr.',
    'confirmed'      => ':attribute tassyklamasy deň däl.',
    'date'           => ':attribute dogry sene bolmalydyr.',
    'date_equals'    => 'The :attribute must be a date equal to :date.',
    'date_format'    => ':attribute :format formatyna deň däl.',
    'different'      => ':attribute bilen :other birbirinden tapawutly bolmalydyr.',
    'digits'         => ':attribute :digits san bolmalydyr.',
    'digits_between' => ':attribute :min bilen :max arasynda san bolmalydyr.',
    'dimensions'     => 'The :attribute has invalid image dimensions.',
    'distinct'       => 'The :attribute field has a duplicate value.',
    'email'          => ':attribute formaty ýalňyş.',
    'ends_with'      => 'The :attribute must end with one of the following: :values.',
    'exists'         => 'Saýlanan :attribute ýalňyş.',
    'file'           => 'The :attribute must be a file.',
    'filled'         => ':attribute meýdany zerur.',
    'gt'             => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image'    => ':attribute surat bolmalydyr.',
    'in'       => ':attribute ýalňyş.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer'  => ':attribute san bolmalydyr.',
    'ip'       => ':attribute dogry IP adres bolmalydyr.',
    'ipv4'     => 'The :attribute must be a valid IPv4 address.',
    'ipv6'     => 'The :attribute must be a valid IPv6 address.',
    'json'     => 'The :attribute must be a valid JSON string.',
    'lt'       => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute :max den kiçi bolmalydyr.',
        'file'    => 'Faýl :max kilobaýtdan kiçi bolmalydyr.',
        'string'  => ':attribute :max harpdan kiçi bolmalydyr.',
        'array'   => ':attribute iň az :max maddadan ybarat bolmalydyr.',
    ],
    'mimes'     => 'Faýlyň formaty :values bolmalydyr.',
    'mimetypes' => ':attribute faýlň formaty :values bolmalydyr.',
    'min'       => [
        'numeric' => ':attribute mukdary :min dan köp bolmalydyr.',
        'file'    => ':attribute mukdary :min kilobaýtdan köp bolmalydyr.',
        'string'  => ':attribute mukdary :min harpdan köp bolmalydyr.',
        'array'   => ':attribute iň az :min harpdan bolmalydyr.',
    ],
    'multiple_of'          => 'The :attribute must be a multiple of :value',
    'not_in'               => 'Saýlanan :attribute geçersiz.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => ':attribute san bolmalydyr.',
    'password'             => 'The password is incorrect.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => ':attribute formaty ýalňyş.',
    'required'             => ':attribute meýdany zerur.',
    'required_if'          => ':attribute meýdany, :other :value hümmetine eýe bolanynda zerurdyr.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute meýdany :values bar bolanda zerurdyr.',
    'required_with_all'    => ':attribute meýdany haýsyda bolsa bir :values bar bolanda zerurdyr.',
    'required_without'     => ':attribute meýdany :values ýok bolanda zerurdyr.',
    'required_without_all' => ':attribute meýdany :values dan haýsyda bolsa biri ýok bolanda zerurdyr.',
    'same'                 => ':attribute bilen :other deň bolmalydyr.',
    'size'                 => [
        'numeric' => ':attribute :size sandan ybarat bolmalydyr.',
        'file'    => ':attribute :size kilobaýt bolmalydyr.',
        'string'  => ':attribute :size harp bolmalydyr.',
        'array'   => ':attribute :size madda eýe bolmalydyr.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string'      => 'The :attribute must be a string.',
    'timezone'    => ':attribute dogry zolak bolmalydyr.',
    'unique'      => ':attribute önden hasaba alyndy.',
    'uploaded'    => 'The :attribute failed to upload.',
    'url'         => ':attribute formaty ýalňyş.',
    'uuid'        => 'The :attribute must be a valid UUID.',
    'atleast_1_image' => 'Azyndan 1 surat ýüklemeli.',
    'image_type' => 'Diňe :image_type ýüklemek bolýar!',
    'image_size' => 'Surat :size Mb-dan uly bolmaly däl!',
    'low_balance' => 'Hasabyňyzy doldurmagyňyzy haýyş edýäris',
    'balance' => [
        'fill_up_succes' => 'Balans üstünlikli dolduryldy.',
        'fill_up_fail' => 'Balans doldurylmady. Soňrak synanyşyp görüň.',
        'bank_service_unavailable' => 'Bank hyzmatlaryna birigip bolmaýar. Soňrak synanyşyp görüň.'
    ],
    'auth_profile' => [
        'phone_number_required' => 'Telefon belgi meýdany zerur.',
        'phone_number_numeric' => 'Telefon belgi sandlardan ybarat bolmaly.',
        'phone_number_digits_between' => 'Telefon belgi 8-20 sany sanlardan ybarat bolmaly.',
        'phone_number_unique' => 'Telefon belgi öňden hasaba alyndy.',
        'iu_about_digits' => 'Legalizasiýa nomeri 6 sany sandan ybarat bolmaly.',
        'iu_company_max' => 'Kompaniýanyň ady 191 sany harpdan geçmeli däl.',
    ],
    'no_user' => 'Beýle ulanyjy ýok.',
    'post_delete_confirm' => 'Bu bildiriş saýtda ýerleşdirilen. Bu bildirişi pozmak isleýärsiňizmi? Soň bu bildirişi dikeldip bolmaz.',
    'thanks_for_posting' => 'Ugradan ýüzlenmäňiz üçin sag boluň!',
    'new_message' => 'Täze hat',
    'payment_reviewed' => 'Töleg gözden geçirildi',
    'product_reviewed' => 'Bildiriş gözden geçirildi',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name_en' => 'Lotyň ady EN',
        'name_ru' => 'Lotyň ady RU',
        'name_tm' => 'Lotyň ady TM',
        'mark' => 'Harydyň markasy',
        'manufacturer' => 'Öndüriji',
        'category_id' => 'Kategoriýa',
        'country' => 'Öndürijiniň yurdy',
        'measure_id' => 'Birlikler',
        'quantity' => 'Harydyň mukdary',
        'currency_id' => 'Pul birligi',
        'price' => 'Bahasy',
        'delivery_term_id' => 'Üpjün etmegiň şertleri',
        'place' => 'Barmaly ýeri',
        'packaging' => 'Gaplama',
        'payment_term_id' => 'Töleg şertleri',
        'description_ru' => 'Beýany RU',
        'description_en' => 'Beýany EN',
        'description_tm' => 'Beýany TM',
        'bank_file' => 'Bankdan faýl',
        'market_type' => 'Bazaryň gornüşi',
        'password' => 'Açar söz',
        'email' => 'El. bukjaňyz',
        'name' => 'At',
        'surname' => 'Familiýa',
        'mobile' => 'Telefon belgiňiz',
    ],

];
