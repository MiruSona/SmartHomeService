using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class WeatherCanvas : MonoBehaviour {

    //데이터
    private WebConnect web_connect;

    //타겟
    public WeatherEffect target;
    private TargetManager target_manager;

    //컴포넌트
    private GameObject ar_canvas;
    private Text ar_text;

    //인덱스
    private string current_indx = "0";
    private string today_index = "0";

    //초기화
    void Start()
    {
        //데이터
        web_connect = WebConnect.instance;

        //타겟매니져 받기
        target_manager = target.GetComponent<TargetManager>();

        //기본적으로 UI꺼두기
        ar_canvas = transform.FindChild("ARCanvas").gameObject;
        ar_canvas.gameObject.SetActive(false);

        //텍스트 연결
        ar_text = ar_canvas.transform.FindChild("ARText").GetComponent<Text>();
    }

    //ARCanvas 처리
    void Update()
    {
        //타겟이 인식됬다면
        if (target_manager.GetDetected())
        {
            //UI켜기
            if (!ar_canvas.activeSelf)
                ar_canvas.SetActive(true);

            //UI이동
            Vector3 pos = target.transform.localPosition;
            pos.z -= 30.0f;
            transform.localPosition = pos;

            //텍스트 표시
            ChangeText();
        }
        //타겟이 벗어났으면 UI끄기
        else
            ar_canvas.SetActive(false);
    }

    //텍스트 변경
    private void ChangeText()
    {
        //날씨 데이터가 있으면 표시!
        if (web_connect.weather_data != null)
        {
            //상태에따라 텍스트 표시
            if(current_indx == today_index)
            {
                ar_text.text =
                "오늘:" + web_connect.weather_data[current_indx]["wfkor"].ToString() + "\n" +
                "비:" + web_connect.weather_data[current_indx]["pop"].ToString() + "%";
            }
            else
            {
                ar_text.text =
                "내일:" + web_connect.weather_data[current_indx]["wfkor"].ToString() + "\n" +
                "비:" + web_connect.weather_data[current_indx]["pop"].ToString() + "%";
            }
            
        }
        //아니면 기다리라 표시!
        else
        {
            ar_text.text = "잠시만 기다려 주세요!";
        }
    }

    //AR버튼
    public void ARBtn()
    {
        //인덱스 변경
        if (current_indx == today_index)
            current_indx = web_connect.tomorrow_index;
        else
            current_indx = today_index;

        //이펙트 변경
        target.EffectChange(current_indx);
    }
}
