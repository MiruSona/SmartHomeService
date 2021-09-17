using UnityEngine;
using System.Collections;

public class BusMove : MonoBehaviour {

    //값
    private float move_max = 1.34f;
    private Vector3 move_min = new Vector3(-1.34f, 0.5f, 0);
    private float move_value = 0.03f;

	//초기화
	void Start () {
        transform.localPosition = move_min;
    }
	
	//움직이기
	void Update () {
        if (gameObject.activeSelf)
        {
            if (transform.localPosition.x < move_max)
            {
                Vector3 pos = transform.localPosition;
                pos.x += move_value;
                transform.localPosition = pos;
            }
            else
                transform.localPosition = move_min;
        }
	}
}
