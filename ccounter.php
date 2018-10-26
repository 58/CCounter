<?php
class CCounter
{
    // セーブファイル
    const COUNT_FILE = 'count.log';
    
    // カウントファイルから任意の値を得る
    private function getLog($mode = NULL)
    {
        // ファイルが読み込めないときにエラー
        if (!is_readable(self::COUNT_FILE)) throw new Exception ('Could not read file.');

        $data = file_get_contents(self::COUNT_FILE);
        if ($data !== FALSE && $data !== '') {
            list($all, $today, $yesterday, $date) = explode(',', $data);

            if ($mode === 'a') {
                return $all;
            }
            if ($mode === 't') {
                return $today;
            }
            if ($mode === 'y') {
                return $yesterday;
            }
            if ($mode === 'd') {
                return $date;
            }

            return $data;
        } else {
            // ファイルの中身がないときにエラー
            throw new Exception ('Not exist contents in file.');
        }
    }

    // カウントアップを行う
    private function incrementCount()
    {
        $new_all = $this->getLog('a') + 1;
        $new_today = $this->getLog('t') + 1;

        // ファイルが書き込めないときにエラー
        if (!is_writeable(self::COUNT_FILE)) throw new Exception ('Could not read file.');

        file_put_contents(self::COUNT_FILE,
                          $new_all . ',' . $new_today . ',' . $this->getLog('y') . ',' . $this->getLog('d'),
                          LOCK_EX);
    }

    // 日付が変わったときのカウンターのリセット
    private function resetLog($now_date = NULL)
    {
        // パラメータがないときにエラー
        if ($now_date === NULL) throw new Exception ('Not found parameter.');

        $log_all = $this->getLog('a');
        $log_today = $this->getLog('t');

        // ファイルが書き込めないときにエラー
        if (!is_writeable(self::COUNT_FILE)) throw new Exception ('Could not read file.');

        file_put_contents(self::COUNT_FILE,
                          $log_all . ',' . '0' . ',' . $log_today . ',' . $now_date,
                          LOCK_EX);
    }

    // カウントの出力整形
    public function putsLog()
    {
        printf('All: %s ', $this->getLog('a'));
        printf('Today: %s ', $this->getLog('t'));
        printf('Yesterday: %s' . PHP_EOL, $this->getLog('y'));
    }

    // コンストラクタ
    public function __construct()
    {
        try {
            // 日付が変わったときにカウントリセット
            $today_date = date('d');
            if ($today_date !== $this->getLog('d')) {
                $this->resetLog($today_date);
            }
            
            // カウントアップ
            $this->incrementCount();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}