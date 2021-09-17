using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class WeatherInfo : MonoBehaviour {

    //데이터
    private WebConnect web_connect;
    private string current_indx = "0";
    private string today_index = "0";

    //컴포넌트
    private Text day, weather, temperature, pty, pop;
    private Button day_btn;
    private Text day_btn_text;

    //갱신 여부
    private bool renew = false;
    
    //켜질때마다 데이터 갱신
    void OnEnable()
    {
        //데이터
        web_connect = WebConnect.instance;
        StartCoroutine(web_connect.ConnenctWebPost(WebConnect.DataKey.Weather, ""));
        renew = false;
    }

    //초기화
    void Start()
    {
        //컴포넌트
        day = transform.FindChild("Info").FindChild("Day").GetComponent<Text>();
        weather = transform.FindChild("Info").FindChild("Weather").GetComponent<Text>();
        temperature = transform.FindChild("Info").FindChild("Temperature").GetComponent<Text>();
        pty = transform.FindChild("Info").FindChild("PTY").GetComponent<Text>();
        pop = transform.FindChild("Info").FindChild("POP").GetComponent<Text>();

        day_btn = transform.FindChild("Info").FindChild("DayBtn").GetComponent<Button>();
        day_btn_text = day_btn.transform.FindChild("Text").GetComponent<Text>();
        day_btn.interactable = false;
    }

    void Update()
    {
        //날씨 데이터가 없으면 기다리라 표시
        if (web_connect.weather_data == null)
        {
            day.text = "데이터 받는중";
            weather.text = "데이터 받는중";
            temperature.text = "데이터 받는중";
            pty.text = "데이터 받는중";
            pop.text = "데이터 받는중";
        }
        //아니면 표시
        else
        {
            //갱신 안했으면 갱신
            if (!renew)
            {
                RenewTexts(today_index);
                day_btn.interactable = true;
            }
        }
    }

    //텍스트 갱신
    private void RenewTexts(string _index)
    {
        if (web_connect.weather_data != null)
        {
            if (web_connect.weather_data[_index]["day"].ToString() != "")
            {
                day.text = web_connect.weather_data[_index]["day"].ToString();
                weather.text = web_connect.weather_data[_index]["wfkor"].ToString();
                temperature.text = web_connect.weather_data[_index]["temp"].ToString() + "℃";
                pty.text = web_connect.weather_data[_index]["pty"].ToString();
                pop.text = web_connect.weather_data[_index]["pop"].ToString() + "%";
                renew = true;
            }
        }
    }

    //날짜 바꾸는 버튼
    public void ChangeDay()
    {
        if(current_indx == today_index)
        {
            current_indx = web_connect.tomorrow_index;
            day_btn_text.text = "오늘 날씨";
            RenewTexts(current_indx);
        }
        else
        {
            current_indx = today_index;
            day_btn_text.text = "내일 날씨";
            RenewTexts(current_indx);
        }
    }

    //스스로 꺼지기
    public void DisableMe()
    {
        gameObject.SetActive(false);
    }
}
