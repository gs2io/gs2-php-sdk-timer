<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Timer;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Timer クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2TimerClient extends AbstractGs2Client {

	public static $ENDPOINT = 'timer';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param array $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * タイマープールリストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* timerPoolId => タイマープールID
	 * 		* ownerId => オーナーID
	 * 		* name => タイマープール名
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeTimerPool($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Timer', 
					'DescribeTimerPool', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool',
					$query);
	}
	
	/**
	 * タイマープールを作成<br>
	 * <br>
	 * GS2-Timer を利用するには、まずタイマープールを作成する必要があります。<br>
	 * タイマープールには複数のタイマーを格納することができます。<br>
	 * 
	 * @param array $request
	 * * name => スタミナプール名
	 * @return array
	 * * item
	 * 	* timerPoolId => タイマープールID
	 * 	* ownerId => オーナーID
	 * 	* name => タイマープール名
	 * 	* createAt => 作成日時
	 */
	public function createTimerPool($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		$query = [];
		return $this->doPost(
					'Gs2Timer', 
					'CreateTimerPool', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool',
					$body,
					$query);
	}

	/**
	 * タイマープールを取得
	 * 
	 * @param array $request
	 * * timerPoolName => タイマープール名
	 * @return array
	 * * item
	 * 	* timerPoolId => タイマープールID
	 * 	* ownerId => オーナーID
	 * 	* name => タイマープール名
	 * 	* createAt => 作成日時
	 */
	public function getTimerPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Timer',
				'GetTimerPool',
				Gs2TimerClient::$ENDPOINT,
				'/timerPool/'. $request['timerPoolName'],
				$query);
	}

	/**
	 * タイマープールを更新
	 *
	 * @param array $request リクエスト
	 *
	 * * timerPoolName => タイマープール名
	 * * description => 説明文
	 */
	public function updateTimerPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		$query = [];
		return $this->doPut(
				'Gs2Timer',
				'UpdateTimerPool',
				Gs2TimerClient::$ENDPOINT,
				'/timerPool/'. $request['timerPoolName'],
				$body,
				$query);
	}
	
	/**
	 * タイマープールを削除
	 * 
	 * @param array $request
	 * * timerPoolName => タイマープール名
	 */
	public function deleteTimerPool($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Timer', 
					'DeleteTimerPool', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool/'. $request['timerPoolName'],
					$query);
	}
	/**
	 * タイマーリストを取得
	 * 
	 * @param array $request
	 * * timerPoolName => タイマープール名
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* timerId => タイマーID
	 * 		* timerPoolId => タイマープールID
	 * 		* ownerId => オーナーID
	 * 		* callbackMethod => HTTPメソッド
	 * 		* callbackUrl => コールバックURL
	 * 		* callbackBody => コールバックボディ
	 * 		* executeTime => 実行時間
	 * 		* retryMax => 最大リトライ回数
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeTimer($request, $pageToken = NULL, $limit = NULL) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Timer', 
					'DescribeTimer', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool/'. $request['timerPoolName']. '/timer',
					$query);
	}
	
	/**
	 * タイマーを作成<br>
	 * <br>
	 * タイマーを作成すると、指定した時刻に指定したURLに指定したパラメータを持ったアクセスを発生させます。<br>
	 * 基本的には指定した時刻以降に60秒以内に呼び出し処理が開始されます。<br>
	 * 混雑時には60秒以上かかることがありますので、タイミングがシビアな処理には向きません。<br>
	 * <br>
	 * アカウントBANを指定した時刻付近で解除する。など、タイミングがシビアでない処理で利用することをおすすめします。<br>
	 * 
	 * @param array $request
	 * * callbackMethod => HTTPメソッド
	 * * callbackUrl => コールバックURL
	 * * callbackBody => コールバックボディ
	 * * executeTime => 実行時間
	 * * retryMax => 最大リトライ回数(OPTIONAL)
	 * @return array
	 * * item
	 * 	* timerId => タイマーID
	 * 	* timerPoolId => タイマープールID
	 * 	* ownerId => オーナーID
	 * 	* callbackMethod => HTTPメソッド
	 * 	* callbackUrl => コールバックURL
	 * 	* callbackBody => コールバックボディ
	 * 	* executeTime => 実行時間
	 * 	* retryMax => 最大リトライ回数
	 * 	* createAt => 作成日時
	 */
	public function createTimer($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('callbackMethod', $request)) $body['callbackMethod'] = $request['callbackMethod'];
		if(array_key_exists('callbackUrl', $request)) $body['callbackUrl'] = $request['callbackUrl'];
		if(array_key_exists('executeTime', $request)) $body['executeTime'] = $request['executeTime'];
		if(array_key_exists('retryMax', $request)) $body['retryMax'] = $request['retryMax'];
		$query = [];
		return $this->doPost(
					'Gs2Timer', 
					'CreateTimer', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool/'. $request['timerPoolName']. '/timer',
					$body,
					$query);
	}

	/**
	 * タイマーを取得
	 * 
	 * @param array $request
	 * * timerPoolName => タイマープール名
	 * * timerId => タイマーID
	 * @return array
	 * * item
	 * 	* timerId => タイマーID
	 * 	* timerPoolId => タイマープールID
	 * 	* ownerId => オーナーID
	 * 	* callbackMethod => HTTPメソッド
	 * 	* callbackUrl => コールバックURL
	 * 	* callbackBody => コールバックボディ
	 * 	* executeTime => 実行時間
	 * 	* retryMax => 最大リトライ回数
	 * 	* createAt => 作成日時
	 */
	public function getTimer($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		if(!array_key_exists('timerId', $request)) throw new NullPointerException();
		if(is_null($request['timerId'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Timer',
				'GetTimer',
				Gs2TimerClient::$ENDPOINT,
				'/timerPool/'. $request['timerPoolName']. '/timer/'. $request['timerId'],
				$query);
	}
	
	/**
	 * タイマーを削除
	 * 
	 * @param array $request
	 * * timerPoolName => タイマープール名
	 * * timerId => タイマーID
	 */
	public function deleteTimer($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('timerPoolName', $request)) throw new NullPointerException();
		if(is_null($request['timerPoolName'])) throw new NullPointerException();
		if(!array_key_exists('timerId', $request)) throw new NullPointerException();
		if(is_null($request['timerId'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Timer', 
					'DeleteTimer', 
					Gs2TimerClient::$ENDPOINT, 
					'/timerPool/'. $request['timerPoolName']. '/timer/'. $request['timerId'],
					$query);
	}
}