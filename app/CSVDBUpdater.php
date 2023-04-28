<?

class CSVDBUpdater extends \DB\Connection
{
    protected $tableName = '';

    public function __construct($config, $tableName)
    {
        parent::__construct($config);

        $this->tableName = $tableName;
    }


    public function makeUpdate($parsedCsv)
    {
        if (!is_array($parsedCsv) and !$parsedCsv['columns'] and !$parsedCsv['rows'])
            throw new Exception('Columns and Rows is Required', 500);

        $this->clearTable();

        $insertQuery = array();

        foreach ($parsedCsv['rows'] as $row)
            $insertQuery[] = $this->prepareInsertRow($row);


        $this->makeInsertQuery($parsedCsv['columns'], $insertQuery);

        return true;
    }




    private function clearTable()
    {
        $this->query('TRUNCATE TABLE ' . $this->tableName);
    }

    private function makeInsertQuery($tableColumns, $insertQueryValues)
    {
        $tableColumns = $this->getReadyValues($tableColumns, '`');
        $insertQueryValues = implode(',', $insertQueryValues);

        $this->query('INSERT INTO `' . $this->tableName . '` (' . $tableColumns . ') VALUES ' . $insertQueryValues . ';');
    }


    private function prepareInsertRow($rowvalues)
    {
        return '(' . $this->getReadyValues($rowvalues) . ')';
    }

    private function getReadyValues($rowValues, $quot = '\'')
    {
        foreach ($rowValues as &$val)
            if (!is_numeric($val))
                $val = $quot . $val . $quot;

        return implode(',', $rowValues);
    }
}
