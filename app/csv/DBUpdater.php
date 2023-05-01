<?

namespace CSV;

class DBUpdater extends \DB\Connection
{
    protected $tableName = '';
    protected $inertInterval = 100000;

    public function __construct($config, $tableName)
    {
        parent::__construct($config);

        $this->tableName = $tableName;
    }


    public function makeUpdate($parsedCsv)
    {
        if (!is_array($parsedCsv) and !$parsedCsv['columns'] and !$parsedCsv['rows'])
            throw new \Exception('Columns and Rows is Required', 500);

        $this->clearTable();

        $insertQuery = array();

        $this->createInsertQueries($parsedCsv, $insertQuery);

        foreach($insertQuery as $query)
            $this->makeInsertQuery($parsedCsv['columns'], $query);

        return true;
    }




    private function createInsertQueries(&$parsedCsv, &$insertQuery)
    {
        $insertQuery = array();

        $intervalQuery = array();

        $intervalCount = 0;

        foreach ($parsedCsv['rows'] as $row) {
            $intervalCount++;
            $intervalQuery[] = $this->prepareInsertRow($row);

            if ($intervalCount > $this->inertInterval) {

                $insertQuery[] = $intervalQuery;

                $intervalCount = 0;
                $intervalQuery = array();
            }
        }

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
