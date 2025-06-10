## Задача поставлена так:
- Создать конфиг из которого в Laravel берется имя файла;
- В файле лежит список строк _data _data_1 _data_2 _data_3 _data_2_1 _data_3_1 _data_3_2 и все они потомки _data;
- Образуют дерево;
- Сделать команду Laravel
  которая берет из конфига файл, читает его, преобразует плоскую структуру в массив вида
  fileName_data => [
       fileName_data_1 => [],
       fileName_data_2 => [
           fileName_data_2_1 => []
       ],
       fileName_data_3 => [
          fileName_data_3_1 => [],
          fileName_data_3_2 => []
       ]
  ]
