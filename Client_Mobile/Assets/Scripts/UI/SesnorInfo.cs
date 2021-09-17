using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class SesnorInfo : MonoBehaviour {

    //데이터
    private WebConnect web_connect;

    //컴포넌트
    private Text dust, temperature, humidity, gas;

    //일정 시간마다 갱신
    private Define.Timer renew_timer = new Define.Timer(2, 2);
    
    //초기화
    void Start()
    {
        //데이터
        web_connect = WebConnect.instance;

        //컴포넌트
        dust = transform.FindChild("Info").FindChild("DustSensor").GetComponent<Text>();
        temperature = transform.FindChild("Info").FindChild("TempSensor").GetComponent<Text>();
        humidity = transform.FindChild("Info").FindChild("HumiditySensor").GetComponent<Text>();
        gas = transform.FindChild("Info").FindChild("GasSensor").GetComponent<Text>();
    }

    void Update()
    {
        //데이터가 없으면 기다리라 표시
        if (!web_connect.sensor_datas.ContainsKey(WebConnect.SensorKey.Gas))
        {
            dust.text = "데이터 받는중";
            temperature.text = "데이터 받는중";
            humidity.text = "데이터 받는중";
            gas.text = "데이터 받는중";
        }
        //아니면 표시 및 데이터 갱신
        else
        {
            //텍스트 갱신
            RenewTexts();

            //데이터 갱신
            if (renew_timer.CheckTimer())
            {
                StartCoroutine(web_connect.ConnenctWebPost(WebConnect.SensorKey.Dust));
                StartCoroutine(web_connect.ConnenctWebPost(WebConnect.SensorKey.Temperature));
                StartCoroutine(web_connect.ConnenctWebPost(WebConnect.SensorKey.Humidity));
                StartCoroutine(web_connect.ConnenctWebPost(WebConnect.SensorKey.Gas));
            }
        }
    }

    //텍스트 갱신
    private void RenewTexts()
    {
        dust.text = web_connect.sensor_datas[WebConnect.SensorKey.Dust].ToString();
        temperature.text = web_connect.sensor_datas[WebConnect.SensorKey.Temperature].ToString();
        humidity.text = web_connect.sensor_datas[WebConnect.SensorKey.Humidity].ToString();
        gas.text = web_connect.sensor_datas[WebConnect.SensorKey.Gas].ToString();
    }

    //스스로 꺼지기
    public void DisableMe()
    {
        gameObject.SetActive(false);
    }
}
