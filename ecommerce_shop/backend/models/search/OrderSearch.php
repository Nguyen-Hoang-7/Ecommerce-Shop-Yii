<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */

    public $fullname;

    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'created_by'], 'integer'],
            [['total_price'], 'number'],
            [['firstName', 'lastName', 'fullname', 'email', 'transaction_id', 'paypal_order_id'], 'safe'],
        ];
    }

    public function  attributes()
    {
        return array_merge(parent::attributes(), ['fullname']);
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'fullname' => function () {
                return $this->firstName . ' ' . $this->lastName;
            }
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);
        $dataProvider->sort->attributes['fullname'] = [
            'label' => 'Full Name',
            'asc' => ['firstName' => SORT_ASC, 'lastName' => SORT_ASC],
            'desc' => ['firstName' => SORT_DESC, 'lastName' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->fullname) {
            $query->andWhere("CONCAT(firstName, ' ', lastName) LIKE :fullname",  [':fullname' => "%{$this->fullname}%"]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['ilike', 'firstName', $this->firstName])
            ->andFilterWhere(['ilike', 'lastName', $this->lastName])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['ilike', 'paypal_order_id', $this->paypal_order_id]);

        return $dataProvider;
    }
}
