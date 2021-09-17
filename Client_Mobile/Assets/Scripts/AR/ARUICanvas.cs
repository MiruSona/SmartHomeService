using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class ARUICanvas : MonoBehaviour {

    //데이터
    private WebConnect web_connect;

    //타겟
    public Transform target;
    private TargetManager target_manager;

    //컴포넌트
    private GameObject ar_canvas;
    private Text ar_text;

    //어떤 센서값 가져올지
    public WebConnect.SensorKey sensor_key = WebConnect.SensorKey.Dust;

    //텍스트 관련
    public float boundary_min = 20.0f;
    public float boundary_max = 80.0f;
    public string larger_text = "크다";
    public string smaller_text = "적다";
    public string proper_text = "적절하다";

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
            if(!ar_canvas.activeSelf)
                ar_canvas.SetActive(true);

            //UI이동
            Vector3 pos = target.localPosition;
            pos.z -= 30.0f;
            transform.localPosition = pos;

            //데이터가 있으면 문구 토글
            if (web_connect.sensor_datas.ContainsKey(sensor_key))
                ToggleText();
        }
        //타겟이 벗어났으면 UI끄기
        else
            ar_canvas.SetActive(false);
    }

    //문구 토글
    private void ToggleText()
    {
        if (web_connect.sensor_datas[sensor_key] > boundary_max)
            ar_text.text = larger_text;
        else if (web_connect.sensor_datas[sensor_key] < boundary_min)
            ar_text.text = smaller_text;
        else
            ar_text.text = proper_text;
    }
}
