using UnityEngine;
using System.Collections;

public class WeatherEffect : MonoBehaviour {

    //데이터
    private WebConnect web_connect;

    //참조
    private GameObject[] effects = new GameObject[7];

    //컴포넌트
    private TargetManager target_manager;

    //갱신 여부
    private bool renew_data = false;
    private bool renew_effect = false;

    //인덱스
    private string today_indx = "0";

    //초기화
    void Start()
    {
        web_connect = WebConnect.instance;
        effects[0] = transform.FindChild("Clear").gameObject;
        effects[1] = transform.FindChild("PartlyCloudy").gameObject;
        effects[2] = transform.FindChild("MostlyCloudy").gameObject;
        effects[3] = transform.FindChild("Cloudy").gameObject;
        effects[4] = transform.FindChild("Rain").gameObject;
        effects[5] = transform.FindChild("RainSnow").gameObject;
        effects[6] = transform.FindChild("Snow").gameObject;
        target_manager = GetComponent<TargetManager>();
    }

    //이펙트 ON/OFF
    void Update()
    {
        //타겟 인식 확인
        if (target_manager.GetDetected())
        {
            //날씨 데이터가 있으면 효과 표시!
            if(web_connect.weather_data != null)
            {
                //한번만 이펙트 표시
                if (!renew_effect)
                {
                    EffectChange(today_indx);
                    renew_effect = true;
                }
            }
            //갱신 여부 체크
            if (!renew_data)
            {
                //날씨값 갱신
                StartCoroutine(web_connect.ConnenctWebPost(WebConnect.DataKey.Weather, ""));
                renew_data = true;
            }
        }
        //인식 안됬으면 죄다 끄기
        else
        {
            for (int i = 0; i < effects.Length; i++)
                effects[i].SetActive(false);

            renew_data = false;
            renew_effect = false;
        }
    }

    //이펙트 바꾸기
    public void EffectChange(string _index)
    {
        //이펙트 끄기
        for (int i = 0; i < effects.Length; i++)
            effects[i].SetActive(false);

        //인덱스에 따라 이펙트 표시
        switch (web_connect.weather_data[_index]["wfkor"].ToString())
        {
            case "맑음":
                effects[0].SetActive(true);
                break;
            case "구름 조금":
                effects[1].SetActive(true);
                break;
            case "구름 많음":
                effects[2].SetActive(true);
                break;
            case "흐림":
                effects[3].SetActive(true);
                break;
            case "비":
                effects[4].SetActive(true);
                break;
            case "눈/비":
                effects[5].SetActive(true);
                break;
            case "눈":
                effects[6].SetActive(true);
                break;
        }
    }
}
