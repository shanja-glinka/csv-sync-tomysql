<?

class CSVParser
{

    public function splitCSV(&$csvstr)
    {
        return $this->parseCSV($csvstr);
    }

    private function parseCSV(&$csvstr)
    {
        if (!is_string($csvstr))
            throw new Exception('CSV content mast be a string \'' . gettype($csvstr) . '\' given', 400);

        $splitted = explode("\n", $csvstr);

        if (!is_array($splitted) or !count($splitted))
            throw new Exception('Cannot parse .csv', 400);

        $rows = array();
        foreach ($splitted as $rowId => $values) {
            $rows[$rowId] = $this->parseCSVRow($values);
        }

        $columnNames = $this->getColumnNames($rows);

        return array(
            'columns' => $columnNames,
            'rows' => $rows
        );
    }

    private function parseCSVRow($row)
    {
        return (is_string($row) ? array_map('trim', explode(',', $row)) : $row);
    }

    private function getColumnNames(&$rows)
    {
        if (!count($rows))
            throw new Exception('Column names not found .csv', 400);

        $columnNames = $rows[0];

        unset($rows[0]);

        return $columnNames;
    }
}
