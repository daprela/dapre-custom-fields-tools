import React, { useRef, useState, useEffect } from "react";
import styles from "./arrow.module.scss";

function Arrow({ title, arrowType, arrowClick: harrowClickProp }) {
  const [arrowContent, setArrowContent] = useState("➕");
  const [arrowTitle, setArrowTitle] = useState("");
  const ref = useRef(null);

  useEffect(() => {
    setArrowContent(() => (arrowType === "+" ? "➕" : "➖"));
    setArrowTitle(() =>
      arrowType === "+" ? "Add another row" : "Remove next row"
    );
  }, [arrowType]);

  function handleArrowClick(e) {
    let index;

    if (arrowType === "+") {
      index = e.target.parentElement.dataset.index;
    } else {
      index = e.target.parentElement.nextElementSibling.dataset.index;
    }
    harrowClickProp(index, arrowType);
  }

  return (
    <button
      type="button"
      className={`${styles.arrow}`}
      title={arrowTitle}
      onClick={handleArrowClick}
      ref={ref}
    >
      {arrowContent}
    </button>
  );
}

export default Arrow;
