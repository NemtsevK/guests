# Техническое задание

Написать микросервис работы с гостями используя язык программирования на выбор PHP или Go, можно пользоваться любыми opensource пакетами, также возможно реализовать с использованием фреймворков или без них. БД также любая на выбор, использующая SQL в качестве языка запросов.

Микросервис реализует API для CRUD операций над гостем. То есть принимает данные для создания, изменения, получения, удаления записей гостей хранящихся в выбранной базе данных.

Сущность "Гость" Имя, фамилия и телефон – обязательные поля. А поля телефон и email уникальны. В итоге у гостя должны быть следующие атрибуты: идентификатор, имя, фамилия, email, телефон, страна. Если страна не указана, то доставать страну из номера телефона +7 - Россия и т.д.

Правила валидации нужно придумать и реализовать самостоятельно. Микросервис должен запускаться в Docker.

Результат опубликовать в Git репозитории, в него же положить README файл с описанием проекта. Описание не регламентировано, исполнитель сам решает что нужно написать (техническое задание, документация по коду, инструкция для запуска). Также должно быть описание API (как в него делать запросы, какой формат запроса и ответа), можно в любом формате, в том числе в том же README файле.

**Дополнительное обязательное условие для уровня Middle (по желанию для Junior):** “В ответах сервера должны присутствовать два заголовка X-Debug-Time и X-Debug-Memory, которые указывают сколько миллисекунд выполнялся запрос, и сколько Кб памяти потребовалось соответственно.”
