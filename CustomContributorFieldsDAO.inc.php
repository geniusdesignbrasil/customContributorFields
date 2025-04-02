<?php
/**
 * @file plugins/generic/customContributorFields/CustomContributorFieldsDAO.inc.php
 *
 * DAO para armazenar e recuperar campos personalizados dos contribuidores.
 */

import('lib.pkp.classes.db.DAO');

class CustomContributorFieldsDAO extends DAO {

    /**
     * Recupera todos os campos personalizados para uma revista
     */
    function getFieldsByContextId($contextId) {
        $result = $this->retrieve(
            'SELECT * FROM custom_contributor_fields WHERE journal_id = ?',
            [(int) $contextId]
        );

        $fields = [];
        while (!$result->EOF) {
            $fields[] = $result->GetRowAssoc(false);
            $result->MoveNext();
        }
        $result->Close();

        return $fields;
    }

    /**
     * Recupera os valores preenchidos por autor
     */
    function getValuesByAuthorId($authorId) {
        $result = $this->retrieve(
            'SELECT f.field_name, v.value FROM custom_contributor_values v
             JOIN custom_contributor_fields f ON f.id = v.field_id
             WHERE v.author_id = ?',
            [(int) $authorId]
        );

        $values = [];
        while (!$result->EOF) {
            $row = $result->GetRowAssoc(false);
            $values[$row['field_name']] = $row['value'];
            $result->MoveNext();
        }
        $result->Close();

        return $values;
    }

    /**
     * Salva valor de campo personalizado para um autor
     */
    function insertOrUpdateValue($fieldId, $authorId, $value) {
        // Verifica se já existe
        $result = $this->retrieve(
            'SELECT id FROM custom_contributor_values WHERE field_id = ? AND author_id = ?',
            [(int) $fieldId, (int) $authorId]
        );

        if ($result->RecordCount() > 0) {
            $this->update(
                'UPDATE custom_contributor_values SET value = ? WHERE field_id = ? AND author_id = ?',
                [$value, (int) $fieldId, (int) $authorId]
            );
        } else {
            $this->update(
                'INSERT INTO custom_contributor_values (field_id, author_id, value) VALUES (?, ?, ?)',
                [(int) $fieldId, (int) $authorId, $value]
            );
        }
    }

    /**
     * Insere novo campo personalizado
     */
    function insertField($contextId, $fieldName, $fieldType, $isRequired, $isPublic, $showOnForm, $showOnProfile) {
        return $this->update(
            'INSERT INTO custom_contributor_fields (journal_id, field_name, field_type, is_required, is_public, show_on_form, show_on_profile)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $contextId,
                $fieldName,
                $fieldType,
                (int) $isRequired,
                (int) $isPublic,
                (int) $showOnForm,
                (int) $showOnProfile
            ]
        );
    }
}
