using UnityEngine;
using System.Collections;

public class TargetEffect : MonoBehaviour {

    //데이터
    private WebConnect web_connect;

    //참조
    private GameObject effect_larger;
    private GameObject effect_smaller;
    private GameObject effect_proper;

    //컴포넌트
    private TargetManager target_manager;

    //어떤 센서값 가져올지
    public WebConnect.SensorKey sensor_key = WebConnect.SensorKey.Dust;

    //경계값
    public float boundary_min = 20.0f;
    public float boundary_max = 80.0f;

    //데이터 갱신 타이머
    private Define.Timer renew_timer = new Define.Timer(0, 2);

    //초기화
    void Start () {
        web_connect = WebConnect.instance;
        effect_larger = transform.FindChild("Larger").gameObject;
        effect_smaller = transform.FindChild("Smaller").gameObject;
        effect_proper = transform.FindChild("Proper").gameObject;
        target_manager = GetComponent<TargetManager>();
    }
	
	//이펙트 ON/OFF
	void Update () {
        //타겟 인식 확인
        if (target_manager.GetDetected())
        {
            //데이터가 있으면 이펙트 토글
            if (web_connect.sensor_datas.ContainsKey(sensor_key))
                ToggleEffect();

            //데이터 갱신
            if (renew_timer.CheckTimer())
                web_connect.ConnenctWebPost(sensor_key);
        }
        //아니면 전부 끄기
        else
        {
            effect_larger.SetActive(false);
            effect_smaller.SetActive(false);
        }
	}

    //이펙트 토글
    private void ToggleEffect()
    {
        //경계값 넘으면 larger
        if (web_connect.sensor_datas[sensor_key] > boundary_max)
        {
            effect_larger.SetActive(true);
            effect_smaller.SetActive(false);
            effect_proper.SetActive(false);
        }
        //경계값 보다 적으면 smaller
        else if(web_connect.sensor_datas[sensor_key] < boundary_min)
        {
            effect_larger.SetActive(false);
            effect_smaller.SetActive(true);
            effect_proper.SetActive(false);
        }
        //경계값 사이면 proper
        else
        {
            effect_larger.SetActive(false);
            effect_smaller.SetActive(false);
            effect_proper.SetActive(true);
        }
    }
}
